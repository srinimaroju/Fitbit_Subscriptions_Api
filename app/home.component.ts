import { Component, Inject, OnInit } from '@angular/core';
import { Router} from '@angular/router';
import { Location } from '@angular/common';
import { User } from './user';
import { AuthService } from './auth.service';
import {Observable} from 'rxjs/Rx';

// import the WindowRef provider
import {WindowRef} from './WindowRef';

@Component({
 // selector: "div.site-content",
  templateUrl: 'app/home.component.html',
  providers: [ AuthService ]
})
export class HomeComponent implements OnInit { 

  user: User;
  dummyUser: User;
  errorMessage: string;
  active = true;
  submitted = false;
  private subscribeEndPoint = "http://pavans-world.com/fitbit/api/subscribe" ;
  mode = 'Observable';

  constructor(private authService: AuthService, private location:Location,
              private window: WindowRef) { 
  	//console.log(this.authService.get);

  }
  onSubmit() { 
    this.submitted = true; 
    this.authService
          .subscribeToEmail(this.dummyUser.email)
          .subscribe(
                       data => {
                         // this.user = data;
                         this.user.email = this.dummyUser.email;
                          console.log(data);
                           this.authService.subscribeToSleep()
                              .subscribe(
                                 data => {
                                    console.log(data);
                                 },
                              error=> this.handleError);
                         //this.dummyUser = Object.assign({}, this.user);
                       },
                       error=> this.handleError
                );
    this.submitted = false;
  }
  ngOnInit() {
  	console.log("home component inited");
  	this.authService
  				.getProfile()
  				.subscribe(
                       data => {
                       	 this.user = data;
                       	 console.log(this.user);
                         this.dummyUser = Object.assign({}, this.user);
                       },
                       error=> this.handleError
                );
  } 

  private handleError(error) {
   	console.log(error);
   	this.errorMessage = <any>error;
  }

  redirectToSubscribe() {
    this.window.nativeWindow.location.href = 
    		this.subscribeEndPoint
    	  + "?state=" 
    	  +  encodeURIComponent(this.window.nativeWindow.location.origin + "/login");
  }

}