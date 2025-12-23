import { Component } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { NotificationService } from '../../services/notification.service';
import { CommonModule } from '@angular/common';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { CardModule } from 'primeng/card';
import { PasswordModule } from 'primeng/password';
import { CheckboxModule } from 'primeng/checkbox';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    CardModule,
    PasswordModule,
    CheckboxModule
  ],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  form: FormGroup;
  loading = false;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
    private notification: NotificationService
  ) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(8)]],
      remember: [true]
    });
  }

  login(): void {
    if (this.form.invalid) return;

    this.loading = true;
    const { email, password } = this.form.value;

    this.authService.login(email, password).subscribe({
      next: (response) => {
        this.loading = false;
        this.notification.success('¡Inicio de sesión exitoso!');
        this.router.navigate(['/spaces']);
      },
      error: (err) => {
        this.loading = false;
        this.notification.error('Email o contraseña inválidos');
      }
    });
  }

  goToRegister(): void {
    this.router.navigate(['/register']);
  }
}
