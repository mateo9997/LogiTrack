import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { MaterialModule } from './material.module';

// import { AuthInterceptor } from './core/interceptors/auth.interceptor';
// import { AuthGuard } from './core/guards/auth.guard';

import { AppComponent } from './app.component';
// import { LoginComponent } from './pages/login/login.component';
// import { DashboardComponent } from './pages/dashboard/dashboard.component';

// import { OrderListComponent } from './features/orders/order-list/order-list.component';
// import { OrderDetailComponent } from './features/orders/order-detail/order-detail.component';
// import { OrderFormComponent } from './features/orders/order-form/order-form.component';
//
// import { UserListComponent } from './features/users/user-list/user-list.component';
// import { UserFormComponent } from './features/users/user-form/user-form.component';
//
// import { ReportDashboardComponent } from './features/reports/report-dashboard/report-dashboard.component';

@NgModule({
  declarations: [
    AppComponent
    // LoginComponent,
    // DashboardComponent,
    // OrderListComponent,
    // OrderDetailComponent,
    // OrderFormComponent,
    // UserListComponent,
    // UserFormComponent,
    // ReportDashboardComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    HttpClientModule,
    AppRoutingModule,
    MaterialModule,
    FormsModule,
    ReactiveFormsModule
  ],
  providers: [
    // AuthGuard,
    // { provide: HTTP_INTERCEPTORS, useClass: AuthInterceptor, multi: true }
  ],
  bootstrap: [AppComponent]
})
export class AppModule {}
