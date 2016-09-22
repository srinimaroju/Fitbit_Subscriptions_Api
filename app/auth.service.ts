import { Injectable } from '@angular/core';
import { tokenNotExpired, AuthHttp,AuthHttpError } from 'angular2-jwt';
import { Headers, Http, Response } from '@angular/http';
import { Router } from '@angular/router';
import { Observable }         from 'rxjs/Observable';

import 'rxjs/add/operator/toPromise';

import { User } from './user';

@Injectable()
export class AuthService {

  public user: User;
  private endpoint             = 'http://localhost:8000';

  private subscribeUrl         = '/subscribe';
  private profileUrl           = '/user/get/profile';
  private emailUrl             = '/user/set/email/';
  private sleepSubscribeUrl    = '/user/subscribe/sleep';

  constructor(private http: Http, private authHttp: AuthHttp) {
    console.log("initing auth service");
    //this.user = null;
  }

  login(jwt: string) {
    console.log("logging in");
    localStorage.setItem('id_token', jwt);
    console.log("local storage set");
    //Once logged in update user profile
    //return this.getProfile();
  }

  logout() {
    // To log out, just remove the token and profile
    // from local storage
    localStorage.removeItem('profile');
    localStorage.removeItem('id_token');
  }

  getToken() {
    localStorage.getItem('id_token');
    console.log("called get token");
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

  getProfile (): Observable<User>  {
    console.log("getting profile");
    return this.authHttp.get(this.endpoint + this.profileUrl)
                    .map(this.extractData);
  }

  private extractData(res: Response) {
    let body = res.json();
    return body.result || { };
  }

  loggedIn() {
    return tokenNotExpired();
  }

   private handleError (error: any) {
    // In a real world app, we might use a remote logging infrastructure
    // We'd also dig deeper into the error to get a better message
    let errMsg = (error.message) ? error.message :
      error.status ? `${error.status} - ${error.statusText}` : 'Server error';
    console.error(errMsg); // log to console instead
    return Promise.reject(errMsg);
  }
}