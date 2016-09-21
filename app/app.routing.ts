import { ModuleWithProviders } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { FeaturesComponent }    from './features.component';
import { HomeComponent }    from './home.component';
import { CallbackComponent }    from './callback.component';

const appRoutes: Routes = [
  {
    path: '',
    component: HomeComponent
  },
  {
    path: 'features',
    component: FeaturesComponent
  },
  {
  	path:'login',
  	component: CallbackComponent
  }
];

export const appRoutingProviders: any[] = [

];

export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);