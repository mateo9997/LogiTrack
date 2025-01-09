import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Observable } from 'rxjs';
import Order = jasmine.Order;

@Injectable({ providedIn: 'root' })
export class OrderService {

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
    return this.http.get<Order[]>(`${environment.apiUrl}/orders`, { params });
  }

  /**
   * Create a new order with optional assignedUserId and shipment.
   */
  createOrder(orderData: any): Observable<any> {
    return this.http.post<any>(`${environment.apiUrl}/orders`, orderData);
  }

  /**
   * Get detail for a single order.
   */
  getOrderDetail(id: number): Observable<any> {
    return this.http.get<any>(`${environment.apiUrl}/orders/${id}`);
  }

  /**
   * Update an existing order.
   */
  updateOrder(id: number, orderData: any): Observable<any> {
    return this.http.put<any>(`${environment.apiUrl}/orders/${id}`, orderData);
  }

  /**
   * Delete order by ID.
   */
  deleteOrder(id: number): Observable<any> {
    return this.http.delete(`${environment.apiUrl}/orders/${id}`);
  }
}
