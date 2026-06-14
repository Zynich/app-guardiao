import './bootstrap';

import Alpine from 'alpinejs';
import { portalApp } from './portal-app';
import { inputMasks } from './masks';

window.Alpine = Alpine;

Alpine.data('portalApp', portalApp);
Alpine.data('inputMasks', inputMasks);

Alpine.start();
