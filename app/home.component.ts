import { Component, Inject } from '@angular/core';
import { Router} from '@angular/router';
import {Location} from '@angular/common';
import { AuthService } from './auth.service';

// import the WindowRef provider
import {WindowRef} from './WindowRef';

@Component({
  templateUrl: 'app/home.component.html'
})
export class HomeComponent { 
  constructor(private authService: AuthService, private location:Location,
              private window: WindowRef) { 

  }
  redirectToSubscribe() {
    //console.log('Window object', this.window.nativeWindow);
    this.window.nativeWindow.location.href = "http://pavans-world.com/fitbit/api/subscribe";
  }

}