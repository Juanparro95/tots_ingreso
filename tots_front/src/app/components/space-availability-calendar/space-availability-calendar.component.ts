import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SpaceService } from '../../services/space.service';
import { CalendarModule } from 'primeng/calendar';
import { ButtonModule } from 'primeng/button';

@Component({
  selector: 'app-space-availability-calendar',
  standalone: true,
  imports: [CommonModule, FormsModule, CalendarModule, ButtonModule],
  templateUrl: './space-availability-calendar.component.html',
  styleUrls: ['./space-availability-calendar.component.css']
})
export class SpaceAvailabilityCalendarComponent implements OnInit {
  @Input() spaceId: number | null = null;
  @Output() timeSelected = new EventEmitter<{ date: Date; time: string }>();

  selectedDate: Date | null = null;
  slots: any[] = [];
  loading = false;
  timeSlots = [
    '08:00', '09:00', '10:00', '11:00', '12:00',
    '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'
  ];

  constructor(private spaceService: SpaceService) {}

  ngOnInit(): void {
    this.selectedDate = new Date();
  }

  loadAvailability(): void {
    if (!this.spaceId || !this.selectedDate) return;

    this.loading = true;
    const dateStr = this.formatDateForAPI(this.selectedDate);

    this.spaceService.getAvailableSlots(this.spaceId, dateStr).subscribe({
      next: (response) => {
        this.slots = response.data || [];
        this.loading = false;
      },
      error: () => {
        this.slots = [];
        this.loading = false;
      }
    });
  }

  onDateSelect(): void {
    this.loadAvailability();
  }

  formatDateForAPI(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  getSlotStatus(time: string): boolean {
    const slot = this.slots.find(s => {
      // Extract HH:MM from "YYYY-MM-DD HH:MM:SS" format
      const slotTime = s.time.split(' ')[1]?.substring(0, 5);
      return slotTime === time;
    });
    return slot ? slot.available : false;
  }

  getSlotClass(time: string): string {
    return this.getSlotStatus(time) ? 'slot-available' : 'slot-unavailable';
  }

  onSlotClick(time: string): void {
    if (!this.getSlotStatus(time) || !this.selectedDate) return;

    this.timeSelected.emit({
      date: this.selectedDate,
      time: time
    });
  }
}
