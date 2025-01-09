import {User} from './user.model';
import {Shipment} from './shipment.model';

export interface Order {
  id?: number;
  orderNumber: string;
  status: string;
  assignedUser?: User;
  shipment?: Shipment;
  createdAt?: string;
  updatedAt?: string;
}
