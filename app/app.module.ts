import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AUTH_PROVIDERS, provideAuth, AuthHttpError } from 'angular2-jwt';
import { HttpModule, JsonpModule } from '@angular/http';

import { AppComponent }  from './app.component';
import { FeaturesComponent }      from './features.component';
import { HomeComponent }      from './home.component';
import { CallbackComponent }      from './callback.component';
import { routing } from './app.routing';
import { AuthService } from './auth.service';
import { User } from './user';
import { FormsModule }   from '@angular/forms';
import {APP_BASE_HREF} from '@angular/common';

import { WindowRef } from './WindowRef';

@NgModule({
  imports: [ BrowserModule, routing, HttpModule, JsonpModule, FormsModule ],
  declarations: [ AppComponent, FeaturesComponent, HomeComponent, CallbackComponent ],
  providers: [ AUTH_PROVIDERS, WindowRef,
				  provideAuth({
				    //tokenGetter: AuthService.getToken,
				    globalHeaders: [{'Content-Type':'application/json'}],
				    noTokenScheme: true
				  }) ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }