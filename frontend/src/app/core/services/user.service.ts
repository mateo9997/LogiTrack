import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class UserService {
  private baseUrl = `${environment.apiUrl}/users`;

  constructor(private http: HttpClient) {}

  getUsers() {
    return this.http.get<any[]>(this.baseUrl);
  }

  getUserDetail(id: number) {
    return this.http.get<any>(`${this.baseUrl}/${id}`);
  }

  createUser(userData: any) {
    return this.http.post(this.baseUrl, userData);
  }

  updateUser(id: number, userData: any) {
    return this.http.put(`${this.baseUrl}/${id}`, userData);
  }

  deleteUser(id: number) {
    return this.http.delete(`${this.baseUrl}/${id}`);
  }
}
