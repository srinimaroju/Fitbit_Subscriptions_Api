import { Injectable } from '@angular/core';
import { tokenNotExpired, AuthHttp } from 'angular2-jwt';
import { Headers, Http, Response } from '@angular/http';
import { Router } from '@angular/router';
import { Observable }         from 'rxjs/Observable';
import 'rxjs/add/operator/toPromise';

import { User } from './user';

@Injectable()
export class AuthService {

  public user: User;
  private endpoint='http://localhost:8000';

  private subscribeUrl         = '/subscribe';
  private profileUrl           = '/user/get/profile';
  private emailUrl             = '/user/set/email/';
  private sleepSubscribeUrl    = '/user/subscribe/sleep';

  constructor(private http: Http, private authHttp: AuthHttp) {
    this.user = null;
  }

  login(jwt: Observable<string>) {
    jwt.subscribe(
          data => {
            localStorage.setItem('id_token', data);

            //Once logged in update user profile
            this.getProfile();
          }
        );

    
  }

  logout() {
    // To log out, just remove the token and profile
    // from local storage
    localStorage.removeItem('profile');
    localStorage.removeItem('id_token');
  }

  getToken() {
    console.log("called");
  }

  subscribeToEmail(email) {
    return this.authHttp.
           get(this.endpoint + this.emailUrl + email)
            .subscribe(
              data => 
                {
                     console.log("Subscribed to email result:" + data.json());
                     return this.subscribeToSleep();
                },
              err => this.handleError,
              () => console.log('Request Complete')
            );
  }

  subscribeToSleep() {
    return this.authHttp.
           get(this.endpoint + this.sleepSubscribeUrl )
            .subscribe(
              data => 
                {
                     console.log(data.json());
                },
              err => this.handleError,
              () => console.log('Request Complete')
            );
  }

  getProfile(){
    return this.authHttp.
           get(this.endpoint + this.profileUrl)
            .subscribe(
              data => 
                {
                      this.user = data.json().result; 
                      if(this.user.displayName) {
                        console.log(this.user);
                        //this.subscribeToSleep();
                      }
                },
              err => this.handleError,
              () => console.log('Request Complete')
            );
  }

  loggedIn() {
    return tokenNotExpired();
  }

  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
  }
}