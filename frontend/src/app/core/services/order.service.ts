import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class OrderService {

  private baseURL = `${environment.apiUrl}/orders`;

  constructor(private http: HttpClient) { }

  /**
   * List orders with optional ?status=... & ?assignedUserId=...
   */
  getOrders(status?: string, assignedUserId?: number): Observable<any[]> {
    let params = new HttpParams();
    if (status) {
      params = params.append('status', status);
    }
    if (assignedUserId) {
      params = params.append('assignedUserId', assignedUserId);
    }
    return this.http.get<any>(`${this.baseURL}`, { params });
  }

  /**
   * Create a new order with optional assignedUserId and shipment.
   */
  createOrder(orderData: any): Observable<any> {
    return this.http.post<any>(`${this.baseURL}`, orderData);
  }

  /**
   * Get detail for a single order.
   */
  getOrderDetail(id: number): Observable<any> {
    return this.http.get<any>(`${this.baseURL}/${id}`);
  }

  /**
   * Update an existing order.
   */
  updateOrder(id: number, orderData: any): Observable<any> {
    return this.http.put<any>(`${this.baseURL}/${id}`, orderData);
  }

  /**
   * Delete order by ID.
   */
  deleteOrder(id: number): Observable<any> {
    return this.http.delete(`${this.baseURL}/${id}`);
  }
}
