import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Reservation {
  id?: number;
  space_id: number;
  user_id?: number;
  event_name: string;
  start_time: string;
  end_time: string;
  notes?: string;
  space?: any;
  created_at?: string;
  updated_at?: string;
}

export interface AvailableSlot {
  start: string;
  end: string;
  available: boolean;
}

@Injectable({
  providedIn: 'root'
})
export class ReservationService {
  private apiUrl = 'http://localhost:8000/api/reservations';

  constructor(private http: HttpClient) {}

  getReservations(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  getReservation(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`);
  }

  createReservation(reservation: Reservation): Observable<any> {
    return this.http.post(this.apiUrl, reservation);
  }

  updateReservation(id: number, reservation: Partial<Reservation>): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, reservation);
  }

  deleteReservation(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  getAvailableSlots(spaceId: number, date: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/available-slots`, {
      params: {
        space_id: spaceId.toString(),
        date: date
      }
    });
  }
}
