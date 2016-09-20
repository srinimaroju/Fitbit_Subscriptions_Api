import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AUTH_PROVIDERS } from 'angular2-jwt';
import { HttpModule, JsonpModule } from '@angular/http';

import { AppComponent }  from './app.component';
import { FeaturesComponent }      from './features.component';
import { HomeComponent }      from './home.component';
import { routing } from './app.routing';
import { AuthService } from './auth.service';
import {WindowRef} from './WindowRef';

@NgModule({
  imports: [ BrowserModule, routing, HttpModule, JsonpModule ],
  declarations: [ AppComponent, FeaturesComponent, HomeComponent ],
  providers: [ AUTH_PROVIDERS, AuthService,  WindowRef ],
  bootstrap: [ AppComponent ]
})
export class AppModule { }