import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface Space {
  id?: number;
  name: string;
  description?: string;
  capacity: number;
  location: string;
  image_url?: string;
  hourly_rate: number;
  created_at?: string;
  updated_at?: string;
}

@Injectable({
  providedIn: 'root'
})
export class SpaceService {
  private apiUrl = 'http://localhost:8000/api/spaces';

  constructor(private http: HttpClient) {}

  getSpaces(filters?: {
    min_capacity?: number;
    max_capacity?: number;
    search?: string;
  }): Observable<any> {
    let url = this.apiUrl;
    if (filters) {
      const params = new URLSearchParams();
      if (filters.min_capacity) params.append('min_capacity', filters.min_capacity.toString());
      if (filters.max_capacity) params.append('max_capacity', filters.max_capacity.toString());
      if (filters.search) params.append('search', filters.search);
      const queryString = params.toString();
      if (queryString) url += '?' + queryString;
    }
    return this.http.get(url);
  }

  getSpace(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/${id}`);
  }

  createSpace(space: Space): Observable<any> {
    return this.http.post(this.apiUrl, space);
  }

  updateSpace(id: number, space: Partial<Space>): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, space);
  }

  deleteSpace(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}
