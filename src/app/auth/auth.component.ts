import { Component, OnInit } from '@angular/core';
import { AuthGuard } from '../auth.guard';
import { AuthService } from '../services/auth.service';

@Component({
  selector: 'app-auth',
  templateUrl: './auth.component.html',
  styleUrls: ['./auth.component.css']
})
export class AuthComponent implements OnInit {

  constructor(private authService : AuthService) {  }


  ngOnInit(): void {

  }

  connectYou(login, password)
  {
    this.authService.checkByAuth({login, password})
  }

}
