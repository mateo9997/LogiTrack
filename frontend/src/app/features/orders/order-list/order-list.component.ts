import { Component, OnInit } from '@angular/core';
import { OrderService } from '../../../core/services/order.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-order-list',
  template: `
    <h2>Orders</h2>
    <div class="filters">
      <mat-form-field appearance="outline">
        <mat-label>Status</mat-label>
        <mat-select [(value)]="statusFilter" (selectionChange)="loadOrders()">
          <mat-option value="">All</mat-option>
          <mat-option value="pending">Pending</mat-option>
          <mat-option value="shipped">Shipped</mat-option>
          <mat-option value="delivered">Delivered</mat-option>
        </mat-select>
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Assigned User Id</mat-label>
        <input
            matInput type="number"
            [(ngModel)]="assignedUserIdFilter"
            (ngModelChange)="loadOrders()"
        />
      </mat-form-field>

      <button mat-raised-button color="primary" (click)="createOrder()">
        Create New Order
      </button>
    </div>

    <table mat-table [dataSource]="orders" class="mat-elevation-z8">
      <ng-container matColumnDef="orderNumber">
        <th mat-header-cell *matHeaderCellDef>Order #</th>
        <td mat-cell *matCellDef="let row"> {{ row.orderNumber }}</td>
      </ng-container>

      <ng-container matColumnDef="status">
        <th mat-header-cell *matHeaderCellDef>Status</th>
        <td mat-cell *matCellDef="let row"> {{ row.status }}</td>
      </ng-container>

      <ng-container matColumnDef="assignedUser">
        <th mat-header-cell *matHeaderCellDef>Assigned User</th>
        <td mat-cell *matCellDef="let row">
          {{ row.assignedUser ? row.assignedUser.username: 'N/A'}}
        </td>
      </ng-container>

      <tr mat-header-row *matHeaderRowDef="displayedColumns"></tr>
      <tr
        mat-row
        *matRowDef="let row; columns: displayedColumns"
        (click)="goToDetail(row.id)"
      ></tr>
    </table>
  `,
  styles: [
    `
      .filters {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
      }
    `
  ]
})
export class OrderListComponent implements OnInit {
  orders: any[] = [];
  displayedColumns = ['orderNumber', 'status', 'assignedUser'];
  statusFilter: string = '';
  assignedUserIdFilter?: number;

  constructor(private orderService: OrderService, private router: Router) {}

  ngOnInit(): void {
    this.loadOrders();
  }

  loadOrders(): void {
    this.orderService
      .getOrders(this.statusFilter, this.assignedUserIdFilter)
      .subscribe((data) => (this.orders = data));
  }

  createOrder(){
    this.router.navigate(['/orders', 'new']);
  }

  goToDetail(orderId: number) {
    this.router.navigate(['/orders', orderId]);
  }
}
