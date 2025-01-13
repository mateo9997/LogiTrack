import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ReportService {

  private baseUrl=`${environment.apiUrl}/reports`;

  constructor(private http: HttpClient) { }
  /**
   * Get overview of total orders, status counts.
   */
  getOverview(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/overview`);
  }

  /**
   * Get fulfillment rates (delivered vs total).
   */
  getFulfillmentRates(): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/fulfillment`);
  }
}
