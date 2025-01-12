import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { OrderService } from '../../../core/services/order.service';

@Component({
  selector: 'app-order-detail',
  templateUrl: './order-detail.component.html'
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
