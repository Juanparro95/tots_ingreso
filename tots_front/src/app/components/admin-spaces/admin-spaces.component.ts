import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SpaceService, Space } from '../../services/space.service';
import { NotificationService } from '../../services/notification.service';
import { TableModule } from 'primeng/table';
import { DialogModule } from 'primeng/dialog';
import { InputTextModule } from 'primeng/inputtext';
import { InputNumberModule } from 'primeng/inputnumber';
import { ButtonModule } from 'primeng/button';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { DropdownModule } from 'primeng/dropdown';
import { ConfirmationService } from 'primeng/api';

// Placeholder for MC Kit table: once installed, import proper module/component
// import { MCTableModule } from 'mc-kit';

@Component({
  selector: 'app-admin-spaces',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    TableModule,
    DialogModule,
    InputTextModule,
    InputNumberModule,
    ButtonModule,
    ConfirmDialogModule,
    DropdownModule
  ],
  providers: [ConfirmationService],
  templateUrl: './admin-spaces.component.html',
  styleUrls: ['./admin-spaces.component.css']
})
export class AdminSpacesComponent implements OnInit {
  spaces: Space[] = [];
  loading = false;
  // Form state
  dialogVisible = false;
  editing: Space | null = null;
  form: Partial<Space> = {
    name: '',
    description: '',
    capacity: 1,
    location: '',
    image_url: '',
    hourly_rate: 0
  };

  // Filters
  searchText = '';
  minCapacity: number | null = null;
  maxCapacity: number | null = null;
  selectedType: string | null = null;

  // Type options
  typeOptions = [
    { label: 'Sala', value: 'sala' },
    { label: 'Auditorio', value: 'auditorio' },
    { label: 'Conferencia', value: 'conferencia' },
    { label: 'Taller', value: 'taller' }
  ];

  constructor(
    private spacesApi: SpaceService,
    private notify: NotificationService,
    private confirmationService: ConfirmationService
  ) {}

  ngOnInit(): void {
    this.load();
  }

  load(): void {
    this.loading = true;
    const filters = {
      search: this.searchText || undefined,
      min_capacity: this.minCapacity || undefined,
      max_capacity: this.maxCapacity || undefined,
      type: this.selectedType ? this.selectedType : undefined
    } as any;

    // Remove undefined values
    Object.keys(filters).forEach(key => filters[key] === undefined && delete filters[key]);

    this.spacesApi.getSpaces(filters).subscribe({
      next: res => { this.spaces = res.data; this.loading = false; },
      error: () => { this.notify.error('Error al cargar espacios'); this.loading = false; }
    });
  }

  openCreate(): void {
    this.editing = null;
    this.form = { name: '', description: '', capacity: 1, location: '', image_url: '', hourly_rate: 0 };
    this.dialogVisible = true;
  }

  openEdit(space: Space): void {
    this.editing = space;
    this.form = { ...space };
    this.dialogVisible = true;
  }

  save(): void {
    // Validate required fields
    if (!this.form.name?.trim()) {
      this.notify.error('El nombre es requerido');
      return;
    }
    if (!this.form.type) {
      this.notify.error('El tipo es requerido');
      return;
    }
    if (!this.form.capacity || this.form.capacity < 1) {
      this.notify.error('La capacidad debe ser al menos 1');
      return;
    }
    if (!this.form.location?.trim()) {
      this.notify.error('La ubicación es requerida');
      return;
    }
    if (this.form.hourly_rate === undefined || this.form.hourly_rate === null || this.form.hourly_rate < 0) {
      this.notify.error('El precio por hora es requerido');
      return;
    }

    const payload: Space = {
      name: this.form.name.trim(),
      type: this.form.type,
      description: this.form.description?.trim() || '',
      capacity: Number(this.form.capacity),
      location: this.form.location.trim(),
      image_url: this.form.image_url?.trim() || '',
      hourly_rate: Number(this.form.hourly_rate)
    };

    if (this.editing?.id) {
      this.spacesApi.updateSpace(this.editing.id, payload).subscribe({
        next: () => { this.notify.success('Espacio actualizado'); this.dialogVisible = false; this.load(); },
        error: err => this.notify.error(err.error?.message || 'Error al actualizar')
      });
    } else {
      this.spacesApi.createSpace(payload).subscribe({
        next: () => { this.notify.success('Espacio creado'); this.dialogVisible = false; this.load(); },
        error: err => this.notify.error(err.error?.message || 'Error al crear')
      });
    }
  }

  remove(space: Space): void {
    if (!space.id) return;

    this.confirmationService.confirm({
      message: `¿Está seguro de que desea eliminar "${space.name}"?`,
      header: 'Confirmar eliminación',
      icon: 'pi pi-exclamation-triangle',
      accept: () => {
        this.spacesApi.deleteSpace(space.id!).subscribe({
          next: () => { this.notify.success('Espacio eliminado'); this.load(); },
          error: err => this.notify.error(err.error?.message || 'Error al eliminar')
        });
      },
      reject: () => {}
    });
  }
}
