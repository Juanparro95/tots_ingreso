import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { SpaceService, Space } from '../../services/space.service';
import { NotificationService } from '../../services/notification.service';
import { CardModule } from 'primeng/card';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { InputNumberModule } from 'primeng/inputnumber';
import { TableModule } from 'primeng/table';
import { ToolbarModule } from 'primeng/toolbar';
import { DialogModule } from 'primeng/dialog';
import { CalendarModule } from 'primeng/calendar';
import { DropdownModule } from 'primeng/dropdown';
import { TabViewModule } from 'primeng/tabview';
import { SpaceAvailabilityCalendarComponent } from '../space-availability-calendar/space-availability-calendar.component';

@Component({
  selector: 'app-spaces',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    CardModule,
    ButtonModule,
    InputTextModule,
    InputNumberModule,
    TableModule,
    ToolbarModule,
    DialogModule,
    CalendarModule,
    DropdownModule,
    TabViewModule,
    SpaceAvailabilityCalendarComponent
  ],
  templateUrl: './spaces.component.html',
  styleUrls: ['./spaces.component.css']
})
export class SpacesComponent implements OnInit {
  spaces: Space[] = [];
  selectedSpace: Space | null = null;
  loading = false;
  detailsVisible = false;

  // Filters
  searchText = '';
  minCapacity: number | null = null;
  maxCapacity: number | null = null;
  selectedDate: Date | null = null;

  // Availability
  spaceAvailability: { [key: number]: { available: number; total: number } } = {};

  constructor(
    private spaceService: SpaceService,
    private router: Router,
    private notification: NotificationService
  ) {}

  ngOnInit(): void {
    this.loadSpaces();
  }

  loadSpaces(): void {
    this.loading = true;
    const filters = {
      search: this.searchText || undefined,
      min_capacity: this.minCapacity || undefined,
      max_capacity: this.maxCapacity || undefined
    };

    this.spaceService.getSpaces(filters).subscribe({
      next: (response) => {
        this.spaces = response.data;
        // Load availability for each space if date is selected
        if (this.selectedDate) {
          this.loadAvailability();
        }
        this.loading = false;
      },
      error: (err) => {
        this.notification.error('Error al cargar espacios');
        this.loading = false;
      }
    });
  }

  loadAvailability(): void {
    if (!this.selectedDate) return;

    const dateStr = this.formatDateForAPI(this.selectedDate);

    this.spaces.forEach(space => {
      if (space.id) {
        this.spaceService.getAvailableSlots(space.id, dateStr).subscribe({
          next: (response) => {
            const slots = response.data || [];
            const available = slots.filter((s: any) => s.available).length;
            this.spaceAvailability[space.id!] = {
              available,
              total: slots.length || 1
            };
          },
          error: () => {
            this.spaceAvailability[space.id!] = { available: 0, total: 1 };
          }
        });
      }
    });
  }

  onDateChange(): void {
    this.loadSpaces();
  }

  formatDateForAPI(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  getAvailabilityPercentage(spaceId: number): number {
    const avail = this.spaceAvailability[spaceId];
    if (!avail) return 0;
    return Math.round((avail.available / avail.total) * 100);
  }

  getAvailabilityClass(spaceId: number): string {
    const percentage = this.getAvailabilityPercentage(spaceId);
    if (percentage === 0) return 'availability-none';
    if (percentage < 30) return 'availability-low';
    if (percentage < 70) return 'availability-medium';
    return 'availability-high';
  }

  viewDetails(space: Space): void {
    this.selectedSpace = space;
    this.detailsVisible = true;
  }

  makeReservation(space: Space): void {
    this.router.navigate(['/reservations/new'], {
      queryParams: { spaceId: space.id }
    });
  }

  onTimeSelected(event: { date: Date; time: string }): void {
    if (!this.selectedSpace) return;

    // Create datetime by combining date and time
    const [hours, minutes] = event.time.split(':');

    // Create a new Date object from the selected date
    const startDateTime = new Date(event.date.getTime());
    startDateTime.setHours(parseInt(hours, 10), parseInt(minutes, 10), 0, 0);

    // End time is 1 hour later (same day)
    const endDateTime = new Date(startDateTime.getTime());
    endDateTime.setHours(endDateTime.getHours() + 1);

    // Navigate to reservation form with pre-filled data
    this.router.navigate(['/reservations/new'], {
      queryParams: {
        spaceId: this.selectedSpace.id,
        startTime: startDateTime.toISOString(),
        endTime: endDateTime.toISOString()
      }
    });

    // Close the dialog
    this.detailsVisible = false;
  }

  formatPrice(price: number): string {
    const fmt = new Intl.NumberFormat('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return '$' + fmt.format(price) + ' /hora';
  }
}
