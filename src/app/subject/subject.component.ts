import { Component, OnInit, Input } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiService } from '../services/api.service';


@Component({
  selector: 'app-subject',
  templateUrl: './subject.component.html',
  styleUrls: ['./subject.component.css']
})
export class SubjectComponent implements OnInit {

  user : any;

  constructor(private api: ApiService,private router: ActivatedRoute, private routerRedirect: Router) { 
    
  }

  ngOnInit(): void {
    const id = this.router.snapshot.params['subject'];
    
    this.api.getUserById(+id).
          subscribe((data) => {
            this.user = data;
          },
          error => {
            this.routerRedirect.navigate(['erreur-404']);
          }
          )
  }

}
