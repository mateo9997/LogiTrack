import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { OrderService } from '../../../core/services/order.service';

@Component({
  selector: 'app-order-detail',
  templateUrl: `
    <h2>Order Detail</h2>
    <div *ngIf="order">
        <p><strong>Order #:</strong> {{ order.orderNumber }}</p>
        <p><strong>Status:</strong> {{ order.status }}</p>
        <p>
            <strong>Assigned User:</strong>
            {{ order.assignedUser ? order.assignedUser.username : 'N/A' }}
        </p>
        <div *ngIf="order.shipment">
            <h4>Shipment</h4>
            <p>Carrier: {{ order.shipment,carrierName }}</p>
            <p>Tracking #: {{ order.shipment.trackingNumber }}</p>
            <p>Estimated Delivery: {{ order.shipment.estimatedDelivery }}</p>
        </div>

        <button mat-raised-button color="accent" (click)="editOrder()">Edit</button>
        <button mat-raised-button color="warn" (click)="deleteOrder()">Delete</button>
    </div>
  `
})

export class OrderDetailComponent implements OnInit {
  order: any;
  orderId!: number;

  constructor(
    private route: ActivatedRoute,
    private orderService: OrderService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.order = +this.route.snapshot.params['id'];
    this.loadOrder();
  }

  loadOrder(): void {
    this.orderService.getOrderDetail(this.order.id).subscribe((data) => {
      this.order = data;
    });
  }

  editOrder(): void {
    this.router.navigate(['/orders', this.orderId, 'edit']);
  }

  deleteOrder(): void {
    if(confirm("Are you sure you want to delete this order?")){
      this.orderService.deleteOrder(this.orderId).subscribe((data) => {
        this.router.navigate(['/orders']);
      });
    }
  }
}
