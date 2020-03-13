import { Component, OnInit } from '@angular/core';
import { ApiService } from '../services/api.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-news',
  templateUrl: './news.component.html',
  styleUrls: ['./news.component.css']
})
export class NewsComponent implements OnInit {

  users : any = [];
  p: number = 1; 

  constructor(private usersService: ApiService, private router: Router) { }

  ngOnInit(): void {
    this.usersService.getAllData().
        subscribe((data) => {
          this.users = data;
        },
        error => {
          this.router.navigate(['erreur-404']);
        }
        );
  }

}
