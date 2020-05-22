import { Component } from '@angular/core';
import { NotificationService } from './services/notification/notification.service'
import { HttpClient, HttpHeaders } from '@angular/common/http';
@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  constructor(private alert:NotificationService, private http:HttpClient) { }
  categories_arr = [];
  ngOnInit(): void {
    this.http.get<any>('http://localhost/pos/backend/api/get_categories').subscribe(
      (data) => {
        if(data.success) {
          this.categories_arr = data.data;  
        }
        else {
          for(var i in data.msg) {
            this.alert.showError(data.msg[i], "");
          }  
        }
      },
      (error) => this.alert.showError(error.message, "")
    );
  }
}
