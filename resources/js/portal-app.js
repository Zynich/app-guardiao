export function portalApp() {
    return {
        step: 0,
        loading: false,
        search: '',
        showCategories: false,
        charLimit: 1000,
        errors: {},
        protocol: '',
        dragOver: false,
        copied: false,
        photoFeedback: 0,
        leafletMap: null,
        leafletMarker: null,

        formData: {
            category_id: '',
            category_name: '',
            description: '',
            address: '',
            reference_point: '',
            latitude: '',
            longitude: '',
            name: '',
            phone: '',
            email: '',
            cpf: '',
            photos: [],
        },

        init() {
            this.$watch('step', (value) => {
                if (value === 2) {
                    this.$nextTick(() => {
                        if (!this.leafletMap) {
                            this.initMap();
                        } else {
                            this.leafletMap.invalidateSize();
                        }
                    });
                }
            });
        },

        initMap() {
            if (typeof L === 'undefined') return;

            this.leafletMap = L.map('leaflet-map', {
                center: [-29.9169, -51.1536], // Canoas, RS
                zoom: 12,
                zoomControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(this.leafletMap);

            this.leafletMap.on('click', (e) => {
                this.setMapLocation(e.latlng.lat, e.latlng.lng);
            });
        },

        useMyLocation() {
            if (!navigator.geolocation) {
                alert('Seu dispositivo não suporta geolocalização.');
                return;
            }
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.setMapLocation(pos.coords.latitude, pos.coords.longitude);
                    if (this.leafletMap) {
                        this.leafletMap.setView([pos.coords.latitude, pos.coords.longitude], 16);
                    }
                },
                () => alert('Não foi possível obter sua localização. Verifique as permissões do navegador.')
            );
        },

        async setMapLocation(lat, lng) {
            this.formData.latitude  = lat;
            this.formData.longitude = lng;

            if (this.leafletMarker) {
                this.leafletMarker.setLatLng([lat, lng]);
            } else if (this.leafletMap) {
                this.leafletMarker = L.marker([lat, lng], { draggable: true }).addTo(this.leafletMap);
                this.leafletMarker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.setMapLocation(pos.lat, pos.lng);
                });
            }

            // Geocodificação reversa via Nominatim (OpenStreetMap, sem chave)
            try {
                const res  = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&zoom=18&addressdetails=1&lat=${lat}&lon=${lng}`,
                    { headers: { 'Accept-Language': 'pt-BR', 'User-Agent': 'Guardiao-TCC/1.0' } }
                );
                const data = await res.json();

                if (data?.address) {
                    const a     = data.address;
                    const parts = [];

                    // house_number nem sempre vem no objeto; tenta extrair do display_name
                    // (formato BR: "123, Rua X, Bairro, Cidade, ...")
                    let num = a.house_number;
                    if (!num && data.display_name) {
                        const first = data.display_name.split(',')[0].trim();
                        if (/^\d+[A-Za-z]?$/.test(first)) num = first;
                    }

                    if (a.road)
                        parts.push(a.road + (num ? ', ' + num : ''));
                    else if (a.amenity || a.building || a.tourism)
                        parts.push(a.amenity ?? a.building ?? a.tourism);

                    if (a.suburb || a.neighbourhood || a.quarter)
                        parts.push(a.suburb ?? a.neighbourhood ?? a.quarter);

                    if (a.city || a.town || a.village || a.municipality)
                        parts.push(a.city ?? a.town ?? a.village ?? a.municipality);

                    if (parts.length > 0) this.formData.address = parts.join(', ');
                }
            } catch {
                // falha silenciosa — usuário pode digitar o endereço manualmente
            }
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files).filter((f) => f.type.startsWith('image/'));
            this._addPhotos(files);
            event.target.value = '';
        },

        handleDrop(event) {
            event.preventDefault();
            const files = Array.from(event.dataTransfer.files).filter((f) => f.type.startsWith('image/'));
            this._addPhotos(files);
            this.dragOver = false;
        },

        _addPhotos(files) {
            const before = this.formData.photos.length;
            this.formData.photos = [...this.formData.photos, ...files].slice(0, 5);
            const added = this.formData.photos.length - before;
            if (added > 0) {
                this.photoFeedback = added;
                clearTimeout(this._photoFeedbackTimer);
                this._photoFeedbackTimer = setTimeout(() => { this.photoFeedback = 0; }, 2500);
            }
        },

        removePhoto(index) {
            this.formData.photos.splice(index, 1);
        },

        photoPreviewUrl(file) {
            return URL.createObjectURL(file);
        },

        hasError(field) {
            return !!this.errors[field];
        },

        getError(field) {
            return this.errors[field] ? this.errors[field][0] : '';
        },

        copyProtocol() {
            navigator.clipboard.writeText(this.protocol).then(() => {
                this.copied = true;
                setTimeout(() => (this.copied = false), 2000);
            });
        },

        async getRecaptchaToken() {
            if (!window.grecaptcha || !window.recaptchaSiteKey) return '';
            return new Promise((resolve) => {
                grecaptcha.ready(() => {
                    grecaptcha.execute(window.recaptchaSiteKey, { action: 'submit_ticket' })
                        .then(resolve)
                        .catch(() => resolve(''));
                });
            });
        },

        async submitTicket() {
            if (this.loading) return;
            this.loading = true;
            this.errors  = {};

            const recaptchaToken = await this.getRecaptchaToken();

            const fd = new FormData();
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            fd.append('category_id', this.formData.category_id);
            fd.append('description', this.formData.description);
            fd.append('address', this.formData.address);
            fd.append('reference_point', this.formData.reference_point ?? '');
            if (this.formData.latitude)  fd.append('latitude',  this.formData.latitude);
            if (this.formData.longitude) fd.append('longitude', this.formData.longitude);
            fd.append('citizen_name',  this.formData.name);
            fd.append('citizen_email', this.formData.email);
            fd.append('citizen_phone', this.formData.phone ?? '');
            fd.append('citizen_cpf',   this.formData.cpf   ?? '');
            this.formData.photos.forEach((file, i) => fd.append(`photos[${i}]`, file));
            if (recaptchaToken) fd.append('recaptcha_token', recaptchaToken);

            try {
                const response = await fetch('/ocorrencias', {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });

                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 429) {
                        alert('Muitas tentativas em pouco tempo. Aguarde 60 minutos e tente novamente.');
                        this.loading = false;
                        return;
                    }
                    this.errors = data.errors ?? {};
                    const step1Fields = ['category_id', 'description'];
                    const step2Fields = ['address', 'reference_point'];
                    if (step1Fields.some((f) => this.errors[f]))      this.step = 1;
                    else if (step2Fields.some((f) => this.errors[f])) this.step = 2;
                    else                                               this.step = 3;
                    this.loading = false;
                    return;
                }

                this.protocol = data.protocol;
                this.step     = 4;
            } catch {
                alert('Ocorreu um erro ao enviar. Tente novamente.');
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.step         = 0;
            this.loading      = false;
            this.errors       = {};
            this.protocol     = '';
            this.search       = '';
            this.showCategories = false;
            this.dragOver     = false;
            this.leafletMap   = null;
            this.leafletMarker = null;
            this.formData = {
                category_id: '', category_name: '', description: '',
                address: '', reference_point: '', latitude: '', longitude: '',
                name: '', phone: '', email: '', cpf: '', photos: [],
            };
        },
    };
}
