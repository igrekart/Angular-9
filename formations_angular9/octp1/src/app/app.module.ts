import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NewsComponent } from './news/news.component';
import { SubjectComponent } from './subject/subject.component';
import { PlusComponent } from './plus/plus.component';
import { FourOhForComponent } from './four-oh-for/four-oh-for.component';
import { MapComponent } from './map/map.component';

import { ApiService } from './services/api.service';

import { NgxPaginationModule } from 'ngx-pagination';
import {MatProgressBarModule} from '@angular/material/progress-bar';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthComponent } from './auth/auth.component';
import { AuthGuard } from './auth.guard';

@NgModule({
  declarations: [
    AppComponent,
    NewsComponent,
    SubjectComponent,
    PlusComponent,
    FourOhForComponent,
    MapComponent,
    AuthComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    NgxPaginationModule,
    BrowserAnimationsModule,
    MatProgressBarModule
  ],
  providers: [
    ApiService,
    AuthGuard
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
