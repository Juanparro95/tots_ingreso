import { ApplicationConfig } from '@angular/core';
import { provideRouter, withEnabledBlockingInitialNavigation, withDebugTracing } from '@angular/router';
import { MessageService } from 'primeng/api';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { provideAnimations } from '@angular/platform-browser/animations';

import { routes } from './app.routes';
import { authInterceptor } from './interceptors/auth.interceptor';

export const appConfig: ApplicationConfig = {
  providers: [
    // Ensure the router processes the current URL on startup
    // and add debug tracing to help diagnose navigation issues.
    provideRouter(routes, withEnabledBlockingInitialNavigation(), withDebugTracing()),
    MessageService,
    provideHttpClient(withInterceptors([authInterceptor])),
    provideAnimations()
  ]
};
