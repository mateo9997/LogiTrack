import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, of } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private tokenKey = 'app_token';
  private tokenSubject = new BehaviorSubject<string | null>(null);

  constructor(private http: HttpClient) {
    const storedToken = localStorage.getItem(this.tokenKey);
    this.tokenSubject.next(storedToken);
  }

  login(username: string, password: string): Observable<boolean> {
    const url = `${environment.apiUrl}/auth/login`;
    return this.http.post<{ token: string }>(url, { username, password }).pipe(
      map(res => {
        if (res.token) {
          localStorage.setItem(this.tokenKey, res.token);
          this.tokenSubject.next(res.token);
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

  logout(): void {
    localStorage.removeItem(this.tokenKey);
    this.tokenSubject.next(null);
  }

  getToken(): string | null {
    return this.tokenSubject.value;
  }

  isLoggedIn(): boolean {
    return !!this.getToken();
  }
}
