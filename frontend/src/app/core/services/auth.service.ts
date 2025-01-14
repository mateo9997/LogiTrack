// LogiTrack/frontend/src/app/core/services/auth.service.ts
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, of } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { environment } from '../../../environments/environment';
import { FRONTEND_ROLE_HIERARCHY } from './role-hierarchy';

function decodeJwt(token: string): any {
  try {
    const base64 = token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = atob(base64);
    return JSON.parse(jsonPayload);
  } catch (err) {
    console.error('Invalid JWT token');
    return null;
  }
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private tokenKey = 'app_token';
  private tokenSubject = new BehaviorSubject<string | null>(null);
  private userRoleSubject = new BehaviorSubject<string | null>(null); // e.g. 'ROLE_COORDINATOR'

  constructor(private http: HttpClient) {
    const storedToken = localStorage.getItem(this.tokenKey);
    this.tokenSubject.next(storedToken);
    this.updateRoleFromToken(storedToken);
  }

  /**
   * Sends credentials to /api/auth/login, receives {token}, stores it, decodes role.
   */
  login(username: string, password: string): Observable<boolean> {
    const url = `${environment.apiUrl}/auth/login`;
    return this.http.post<{ token: string }>(url, { username, password }).pipe(
      map(res => {
        if (res.token) {
          localStorage.setItem(this.tokenKey, res.token);
          this.tokenSubject.next(res.token);
          this.updateRoleFromToken(res.token);
          return true;
        }
        return false;
      }),
      catchError(err => {
        console.error('Login error:', err);
        return of(false);
      })
    );
  }

  /**
   * Removes token from storage, resets subject.
   */
  logout(): void {
    localStorage.removeItem(this.tokenKey);
    this.tokenSubject.next(null);
    this.userRoleSubject.next(null);
  }

  /**
   * Returns current JWT token (or null).
   */
  getToken(): string | null {
    return this.tokenSubject.value;
  }

  /**
   * Whether user is logged in based on token presence.
   */
  isLoggedIn(): boolean {
    return !!this.getToken();
  }

  /**
   * Decodes the token to see the user's assigned role (like 'ROLE_COORDINATOR').
   * If the token is invalid or missing, sets userRoleSubject to null.
   */
  private updateRoleFromToken(token: string | null): void {
    if (!token) {
      this.userRoleSubject.next(null);
      return;
    }
    const decoded = decodeJwt(token);
    if (decoded && decoded.roles && decoded.roles.length > 0) {
      this.userRoleSubject.next(decoded.roles[0]);
    } else {
      this.userRoleSubject.next(null);
    }
  }

  /**
   * Returns the user's base role, e.g. 'ROLE_COORDINATOR'.
   */
  getUserRole(): string | null {
    return this.userRoleSubject.value;
  }

  /**
   * Checks if user has at least the specified role, according to the hierarchy.
   * If user's base role is 'ROLE_ADMIN' and we check 'ROLE_COORDINATOR', returns true.
   */
  hasAtLeastRole(requiredRole: string): boolean {
    const userRole = this.userRoleSubject.value;
    if (!userRole) return false;
    if (userRole === requiredRole) return true;

    const subRoles = FRONTEND_ROLE_HIERARCHY[userRole] || [];
    return this.recursiveCheckRole(userRole, requiredRole);
  }

  /**
   * Recursively checks if userRole eventually covers requiredRole using FRONTEND_ROLE_HIERARCHY.
   */
  private recursiveCheckRole(currentRole: string, targetRole: string): boolean {
    if (currentRole === targetRole) return true;
    const subRoles = FRONTEND_ROLE_HIERARCHY[currentRole] || [];
    // If subRoles includes targetRole directly, or includes a role that leads to targetRole
    for (const sr of subRoles) {
      if (sr === targetRole) {
        return true;
      }
      // recursively check
      if (this.recursiveCheckRole(sr, targetRole)) {
        return true;
      }
    }
    return false;
  }
}

