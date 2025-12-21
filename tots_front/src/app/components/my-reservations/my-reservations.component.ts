import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { ReservationService, Reservation } from '../../services/reservation.service';
import { NotificationService } from '../../services/notification.service';
import { CardModule } from 'primeng/card';
import { TagModule } from 'primeng/tag';
import { ButtonModule } from 'primeng/button';
import { TableModule } from 'primeng/table';
import { DialogModule } from 'primeng/dialog';
import { InputTextModule } from 'primeng/inputtext';
import { FormsModule } from '@angular/forms';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { TooltipModule } from 'primeng/tooltip';
import { InputTextareaModule } from 'primeng/inputtextarea';

@Component({
  selector: 'app-my-reservations',
  standalone: true,
  imports: [
    CommonModule,
    CardModule,
    ButtonModule,
    TableModule,
    TagModule,
    DialogModule,
    InputTextModule,
    FormsModule,
    ReactiveFormsModule,
    TooltipModule,
    InputTextareaModule
  ],
  templateUrl: './my-reservations.component.html',
  styleUrls: ['./my-reservations.component.css']
})
export class MyReservationsComponent implements OnInit {
  reservations: Reservation[] = [];
  loading = false;
  editDialogVisible = false;
  editForm: FormGroup;
  editingReservation: Reservation | null = null;

  constructor(
    private reservationService: ReservationService,
    private router: Router,
    private notification: NotificationService,
    private fb: FormBuilder
  ) {
    this.editForm = this.fb.group({
      event_name: ['', Validators.required],
      notes: ['']
    });
  }

  ngOnInit(): void {
    this.loadReservations();
  }

  loadReservations(): void {
    this.loading = true;
    this.reservationService.getReservations().subscribe({
      next: (response) => {
        this.reservations = response.data;
        this.loading = false;
      },
      error: (err) => {
        this.notification.error('Error al cargar reservas');
        this.loading = false;
      }
    });
  }

  editReservation(reservation: Reservation): void {
    this.editingReservation = reservation;
    this.editForm.patchValue({
      event_name: reservation.event_name,
      notes: reservation.notes
    });
    this.editDialogVisible = true;
  }

  saveChanges(): void {
    if (this.editForm.invalid || !this.editingReservation) return;

    const updated = {
      event_name: this.editForm.get('event_name')?.value,
      notes: this.editForm.get('notes')?.value
    };

    this.reservationService.updateReservation(this.editingReservation.id!, updated).subscribe({
      next: () => {
        this.notification.success('Reserva actualizada');
        this.editDialogVisible = false;
        this.loadReservations();
      },
      error: () => {
        this.notification.error('Error al actualizar reserva');
      }
    });
  }

  cancelReservation(id: number): void {
    if (confirm('¿Seguro que deseas cancelar esta reserva?')) {
      this.reservationService.deleteReservation(id).subscribe({
        next: () => {
          this.notification.success('Reserva cancelada');
          this.loadReservations();
        },
        error: () => {
          this.notification.error('Error al cancelar reserva');
        }
      });
    }
  }

  formatDateTime(date: string): string {
    return new Date(date).toLocaleString('es-ES');
  }

  getReservationDuration(startTime: string, endTime: string): number {
    const start = new Date(startTime);
    const end = new Date(endTime);
    return Math.round((end.getTime() - start.getTime()) / (1000 * 60 * 60));
  }

  getStatus(startTime: string): string {
    const start = new Date(startTime);
    const now = new Date();
    return start > now ? 'Próxima' : 'Pasada';
  }

  getStatusSeverity(startTime: string): 'success' | 'info' | 'danger' {
    return this.getStatus(startTime) === 'Próxima' ? 'success' : 'danger';
  }

  newReservation(): void {
    this.router.navigate(['/reservations/new']);
  }
}
