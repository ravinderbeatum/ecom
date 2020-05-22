import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  cart_items_count = 0
  constructor() { }

  ngOnInit(): void {
    if(localStorage.getItem('cart') != null) {
      var count = JSON.parse(localStorage.getItem('cart'));
      this.cart_items_count = count.length;
    }
  }

}
