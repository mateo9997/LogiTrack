import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { OrderService } from '../../../core/services/order.service';
import { UserService } from '../../../core/services/user.service';
import { ActivatedRoute, Router } from '@angular/router';
import {Order} from '../../../core/models/order.model';

@Component({
  selector: 'app-order-form',
  template:`
    <h2>{{ isEdit ? 'Edit Order' : 'Create Order' }}</h2>
    <form [formGroup]="orderForm" (ngSubmit)="onSubmit()">
      <mat-form-field appearance="outline">
        <mat-label>Order Number</mat-label>
        <input matInput formControlName="orderNumber" />
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Order Name</mat-label>
        <input matInput formControlName="orderNumber" />
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Status</mat-label>
        <mat-select formControlName="status">
          <mat-option value="pending">Pending</mat-option>
          <mat-option value="shipped">Shipped</mat-option>
          <mat-option value="delivered">Delivered</mat-option>
        </mat-select>
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Assigned User</mat-label>
        <mat-select formControlName="assignedUserId">
          <mat-option value="null">-- None --</mat-option>
          <mat-option
            *ngFor="let user of users"
            [value]="user.id"
            >
            {{ user.username }}
          </mat-option>
        </mat-select>
      </mat-form-field>

      <h3> Shipment Info (Optional)</h3>
      <div class="shipment-fields">
        <mat-form-field appearance="outline">
          <mat-label>Carrier Name</mat-label>
          <input matInput formControlName="carrierName" />
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Tracking Number</mat-label>
          <input matInput formControlName="trackingNumber" />
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Estimated Delivery</mat-label>
          <input matInput formControlName="estimatedDelivery" />
        </mat-form-field>
      </div>

        <button
          mat-raised-button
          color="primary"
          type="submit"
          [disabled]="orderForm.invalid"
        >
          {{ isEdit ? 'Update Order' : 'Create Order' }}
        </button>
      </form>
  `,
  styles:[
    `
      .shipment-fields {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
      }
      mat-form-field {
        display: block;
        margin-right: 1rem;
      }
    `
  ]

})
export class OrderFormComponent implements OnInit{
  orderForm: FormGroup;
  isEdit: boolean = false;
  orderId?: number;
  users: any[] = [];

  constructor(
    private fb: FormBuilder,
    private orderService: OrderService,
    private userService: UserService,
    private activatedRoute: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.userService.getUsers().subscribe((data) => {this.users = data});

    this.orderId = +this.activatedRoute.snapshot.params['id'];
    this.isEdit = this.activatedRoute.snapshot.url.some((segment) => segment.path === 'edit');

    this.orderForm = this.fb.group({
      orderNumber: ['', Validators.required],
      status: ['pending'],
      assignedUser: [null],
      carrieRnAME: [''],
      trackingNumber: [''],
      estimatedDelivery: ['']
    });

    if(this.isEdit && this.orderId){
      this.loadOrder(this.orderId);
    }
  }

  loadOrder(id: number) {
    this.orderService.getOrderDetail(id).subscribe((order) => {
      this.orderForm.patchValue({
        orderNumber: order.orderNumber,
        status: order.status,
        assignedUserId: order.assignedUserId ? order.assignedUserId : null,
        carrierName: order.shipment? order.shipment.carrierName : '',
        trackingNumber: order.shipment? order.shipment.carrierName : '',
        estimatedDelivery: order.shipment? order.shipment.carrierName : ''
      });
    });
  }

  onSubmit(): void {
    if (this.orderForm.invalid) return;

    const formValue = this.orderForm.value;

    const payload: any = {
      orderNumber: formValue.orderNumber,
      status: formValue.status,
      assignedUserId: formValue.assignedUserId || null,
      shipment: {
        carrierName: formValue.shipment.carrierName,
        trackingNumber: formValue.trackingNumber,
        estimatedDelivery: formValue.estimatedDelivery
      }
    };

    if (this.isEdit && this.orderId){
      this.orderService.updateOrder(this.orderId, payload).subscribe(() =>{
        this.router.navigate(['/orders', this.orderId]);
      });
    } else {
      this.orderService.createOrder(payload).subscribe((newOrder) =>{
        this.router.navigate(['/orders', newOrder.id]);
      });
    }
  }
}
