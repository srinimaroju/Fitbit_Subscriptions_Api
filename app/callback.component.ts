import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ActivatedRoute }     from '@angular/router';
import { Observable }         from 'rxjs/Observable';
import { AuthService } from './auth.service';
import 'rxjs/add/operator/map';

@Component({
  template:`<p>Dashboard</p>
		    <p>Session ID: {{ jwt  }}</p>
		    <a id="anchor"></a>
			`
})

export class CallbackComponent implements OnInit { 
  jwt:  Observable<string>;

  constructor(private route: ActivatedRoute, private authService: AuthService) { 
  	
  }
  
  ngOnInit() {
    // Capture the session ID if available
   this.route
      .queryParams
      .map(params => params['jwt'] || null)
      .subscribe(
          data => {
            this.jwt = data;
            this.authService.login(data);
          }
       );

  }
}