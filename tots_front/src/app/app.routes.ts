import { Routes } from '@angular/router';
import { HomeComponent } from './components/home/home.component';
import { LoginComponent } from './components/login/login.component';
import { RegisterComponent } from './components/register/register.component';
import { SpacesComponent } from './components/spaces/spaces.component';
import { MyReservationsComponent } from './components/my-reservations/my-reservations.component';
import { ReservationFormComponent } from './components/reservation-form/reservation-form.component';
import { AuthGuard } from './guards/auth.guard';
import { AdminGuard } from './guards/admin.guard';

export const routes: Routes = [
  {
    path: '',
    component: HomeComponent
  },
  {
    path: 'login',
    component: LoginComponent
  },
  {
    path: 'register',
    component: RegisterComponent
  },
  {
    path: 'spaces',
    component: SpacesComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'my-reservations',
    component: MyReservationsComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'reservations/new',
    component: ReservationFormComponent,
    canActivate: [AuthGuard]
  },
  {
    path: 'admin',
    loadComponent: () => import('./components/admin-spaces/admin-spaces.component').then(m => m.AdminSpacesComponent),
    canActivate: [AuthGuard, AdminGuard]
  }
];
