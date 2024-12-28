import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { UserService } from '../../../core/services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-user-form',
  template: `
    <h2>Create User</h2>
    <form [formGroup]="userForm" (ngSubmit)="onSubmit()">
      <mat-form-field appearance="outline">
        <mat-label>Username</mat-label>
        <input matInput formControlName="username" />
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Email</mat-label>
        <input matInput formControlName="email" />
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Role</mat-label>
        <mat-select formControlName="role">
          <mat-option value="ROLE_ADMIN">Admin</mat-option>
          <mat-option value="ROLE_COORDINATOR">Coordinator</mat-option>
          <mat-option value="ROLE_WAREHOUSE">Warehouse</mat-option>
        </mat-select>
      </mat-form-field>

      <mat-form-field appearance="outline">
        <mat-label>Password</mat-label>
        <input matInput type="password" formControlName="password" />
      </mat-form-field>

      <button mat-raised-button color="primary" type="submit" [disabled]="userForm.invalid">
        Submit
      </button>
    </form>
  `
})
export class UserFormComponent implements OnInit {
  userForm!: FormGroup;

  constructor(
    private fb: FormBuilder,
    private userService: UserService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.userForm = this.fb.group({
      username: ['', Validators.required],
      email: [''],
      password: ['', Validators.required],
      role: ['ROLE_COORDINATOR']
    });
  }

  onSubmit(): void {
    if (this.userForm.valid) {
      this.userService.createUser(this.userForm.value).subscribe(() => {
        this.router.navigate(['/users']);
      });
    }
  }
}

