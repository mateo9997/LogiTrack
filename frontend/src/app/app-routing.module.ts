import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { authGuardFn } from './core/guards/auth.guard';

import {LoginComponent} from './pages/login/login.component';
import {DashboardComponent} from './pages/dashboard/dashboard.component';
import {UserListComponent} from './features/users/user-list/user-list.component';
import {UserFormComponent} from './features/users/user-form/user-form.component';
import {OrderListComponent} from './features/orders/order-list/order-list.component';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  {
    path: '',
    canActivate: [authGuardFn],
    children: [
      { path: 'dashboard', component: DashboardComponent },
      { path: 'users', component: UserListComponent },
      { path: 'users/:id', component: UserFormComponent },
      { path: 'orders', component: OrderListComponent },
      { path: 'orders/new', component: OrderFormComponent },
      { path: 'orders/:id/edit', component: OrderFormComponent },
      { path: 'orders/:id', component: OrderDetailComponent },
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
    ]
  },
  { path: '**', redirectTo: 'dashboard'}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
