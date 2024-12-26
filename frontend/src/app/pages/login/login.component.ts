import { Component } from '@angular/core';
import { AuthService } from '../../core/services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  template: `
    <h2>Login</h2>
    <form (ngSubmit)="onSubmit()" #loginForm="ngForm">
      <mat-form-field>
        <mat-label>Username</mat-label>
        <input matInput [(ngModel)]="username" name="username" required />
      </mat-form-field>

      <mat-form-field>
        <mat-label>Password</mat-label>
        <input matInput type="password" [(ngModel)]="password" name="password" required />
      </mat-form-field>

      <button mat-raised-button color="primary">Login</button>
    </form>
    <p *ngIf="loginFailed" style="color:red;">Invalid credentials. Please try again.</p>
  `
})
export class LoginComponent {
  username = '';
  password = '';
  loginFailed = false;

  constructor(private authService: AuthService, private router: Router) {}

  onSubmit() {
    this.authService.login(this.username, this.password).subscribe(success => {
      if (success) {
        this.router.navigate(['/dashboard']);
      } else {
        this.loginFailed = true;
      }
    });
  }
}
