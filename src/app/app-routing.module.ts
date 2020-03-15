import { NgModule, Component } from '@angular/core';
import { Routes, RouterModule} from '@angular/router';
import { NewsComponent } from './news/news.component';
import { SubjectComponent } from './subject/subject.component';
import { FourOhForComponent } from './four-oh-for/four-oh-for.component';
import { AuthGuard } from './auth.guard';
import { AuthComponent } from './auth/auth.component';


const routes: Routes = [
  { path: "", canActivate: [AuthGuard], component: NewsComponent },
  { path: "auth", component: AuthComponent},
  { path: "news/:subject", canActivate: [AuthGuard], component: SubjectComponent },
  { path: "erreur-404", component: FourOhForComponent },
  { path: "**", component: FourOhForComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]

})
export class AppRoutingModule { }
