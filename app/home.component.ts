import { Component, Inject, OnInit } from '@angular/core';
import { Router} from '@angular/router';
import { Location } from '@angular/common';
import { User } from './user';
import { AuthService } from './auth.service';

// import the WindowRef provider
import {WindowRef} from './WindowRef';

@Component({
 // selector: "div.site-content",
  templateUrl: 'app/home.component.html',
  providers: [ AuthService ]
})
export class HomeComponent implements OnInit { 

  user: User;
  errorMessage: string;
  private subscribeEndPoint = "http://pavans-world.com/fitbit/api/subscribe" ;
  mode = 'Observable';

  constructor(private authService: AuthService, private location:Location,
              private window: WindowRef) { 
  	//console.log(this.authService.get);

  }

  ngOnInit() {
  	console.log("home component inited");
  	this.authService
  				.getProfile()
  				.subscribe(
                       data => {
                       	 this.user = data;
                       	 console.log(this.user);
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