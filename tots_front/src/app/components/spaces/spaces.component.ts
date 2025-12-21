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
    DialogModule
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
        this.loading = false;
      },
      error: (err) => {
        this.notification.error('Error al cargar espacios');
        this.loading = false;
      }
    });
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

  formatPrice(price: number): string {
    return '$' + price.toFixed(2) + ' /hora';
  }
}
