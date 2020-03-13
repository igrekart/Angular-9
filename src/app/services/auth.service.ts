import { Injectable } from '@angular/core';
import { state } from '@angular/animations';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  state:boolean = false;

userValid = [
  {
    "id": 1,
    "login":"youssoufou",
    "password" : "1234567890"
  },
  {
    "id": 1,
    "login":"mohamed",
    "password" : "1234567890"
  }
]

constructor() {  }

 checker(userCo: any): boolean
 {

   this.userValid.forEach(user => {
     if(user.login == userCo.login && user.password == userCo.password){
       this.state = true;
     }
   });
   return this.state;
 }

}
