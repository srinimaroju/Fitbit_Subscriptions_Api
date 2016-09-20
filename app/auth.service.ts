import { Injectable } from '@angular/core';
import { tokenNotExpired, AuthHttp, AuthConfig, AUTH_PROVIDERS } from 'angular2-jwt';
import { Headers, Http, Response } from '@angular/http';
import { Router } from '@angular/router';
import 'rxjs/add/operator/toPromise';

@Injectable()
export class AuthService {


  constructor(private http: Http, private authHttp: AuthHttp) {
    console.log(this.loggedIn());
  }

  login() {
  }

  logout() {
    // To log out, just remove the token and profile
    // from local storage
    localStorage.removeItem('profile');
    localStorage.removeItem('id_token');
  }

  getProfile() {
    this.authHttp.get('http://pavans-world.com/fitbit/api/user/get/profile')
      .subscribe(
        data => console.log(data),
        err => console.log(err),
        () => console.log('Request Complete')
      );
  }

  loggedIn() {
    return tokenNotExpired();
  }
}