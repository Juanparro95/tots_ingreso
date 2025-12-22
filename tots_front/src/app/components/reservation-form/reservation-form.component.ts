import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ReservationService } from '../../services/reservation.service';
import { SpaceService } from '../../services/space.service';
import { NotificationService } from '../../services/notification.service';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { CardModule } from 'primeng/card';
import { CalendarModule } from 'primeng/calendar';

@Component({
  selector: 'app-reservation-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    ButtonModule,
    InputTextModule,
    CardModule,
    CalendarModule
  ],
  templateUrl: './reservation-form.component.html',
  styleUrls: ['./reservation-form.component.css']
})
export class ReservationFormComponent implements OnInit {
  form: FormGroup;
  loading = false;
  spaceId: number | null = null;
  spaceName = '';

  constructor(
    private fb: FormBuilder,
    private route: ActivatedRoute,
    private router: Router,
    private reservationService: ReservationService,
    private spaceService: SpaceService,
    private notification: NotificationService
  ) {
    this.form = this.fb.group({
      event_name: ['', [Validators.required]],
      start_time: ['', [Validators.required]],
      end_time: ['', [Validators.required]],
      notes: ['', [Validators.maxLength(300)]]
    });
  }

  ngOnInit(): void {
    this.route.queryParams.subscribe(params => {
      if (params['spaceId']) {
        this.spaceId = parseInt(params['spaceId']);
        this.loadSpaceDetails();
      }
    });
  }

  loadSpaceDetails(): void {
    if (!this.spaceId) return;

    this.spaceService.getSpace(this.spaceId).subscribe({
      next: (response) => {
        this.spaceName = response.data.name;
      },
      error: () => {
        this.notification.error('Error al cargar el espacio');
      }
    });
  }

  createReservation(): void {
    if (this.form.invalid || !this.spaceId) return;

    this.loading = true;
    const formValue = this.form.value;

    // Format dates to Y-m-d H:i:s
    const startDateTime = this.formatDateTime(formValue.start_time);
    const endDateTime = this.formatDateTime(formValue.end_time);

    if (startDateTime >= endDateTime) {
      this.notification.error('La hora de fin debe ser posterior a la hora de inicio');
      this.loading = false;
      return;
    }

    const reservation = {
      space_id: this.spaceId,
      event_name: formValue.event_name,
      start_time: startDateTime,
      end_time: endDateTime,
      notes: formValue.notes || null
    };

    this.reservationService.createReservation(reservation).subscribe({
      next: () => {
        this.notification.success('¡Reserva creada exitosamente!');
        this.router.navigate(['/my-reservations']);
      },
      error: (err) => {
        const errorMsg = err.error?.message || 'Error al crear la reserva';
        this.notification.error(errorMsg);
        this.loading = false;
      }
    });
  }

  private formatDateTime(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = '00';
    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
  }

  cancel(): void {
    this.router.navigate(['/spaces']);
  }
}
