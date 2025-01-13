import { Component, OnInit } from '@angular/core';
import { ReportService } from '../../../core/services/report.service';
import {ActivatedRoute} from '@angular/router';

@Component({
  selector: 'app-report-dashboard',
  templateUrl: `
  <h2>Reports Dashboard</h2>
  <div class="overview" *ngIf="overview">
    <h3>Order Overview</h3>
    <p>Total Orders: {{ overview.pending }}</p>
    <p>Pending: {{ overview.pending }}</p>
    <p>Delivered: {{ overview.delivered }}</p>
  </div>

  <div class="fulfillment" *ngIf="fulfillment">
  <h3>Fulfillment Rates</h3>
  <p>Fulfillment Rate: {{ fulfillment.fulfillmentRate }}%</p>
</div>
`,
  styles: [
    `
      .overview,
      .fulfillment {
        margin-bottom: 2rem;
      }
    `
  ]
})
export class ReportDashboardComponent implements OnInit {
  overview: any;
  fulfillment: any;

  constructor(private reportService: ReportService) {}

  ngOnInit(): void {
    this.reportService.getOverview().subscribe((data)=> {
      this.overview = data.overview;
    });

    this.reportService.getFulfillmentRates().subscribe((data)=> {
      this.fulfillment = data;
    });
  }
}
