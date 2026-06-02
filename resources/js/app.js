import './bootstrap';

import Alpine from 'alpinejs';
import { portalApp } from './portal-app';

window.Alpine = Alpine;

Alpine.data('portalApp', portalApp);

Alpine.start();
