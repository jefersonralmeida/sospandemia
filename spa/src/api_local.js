import axios from "axios";
import store from "./store";
import randomstring from "randomstring";
import CryptoJs from 'crypto-js';
import router from './router';

const api = {

    demands: [
        {
            "id": 1,
            "title": "Demanda 1",
            "text": "Descrição da demanda 1 (Castleblack)",
            "quantity": 130,
            "created_at": "2020-03-31T12:48:18.000000Z",
            "updated_at": "2020-03-31T12:48:18.000000Z"
        },
        {
            "id": 2,
            "title": "Demanda 2",
            "text": "Descrição da demanda 2 (Castleblack)",
            "quantity": 10,
            "created_at": "2020-03-31T12:48:18.000000Z",
            "updated_at": "2020-03-31T12:48:18.000000Z"
        }
    ],
    donators: [
        {
            "id": 1,
            "document": "74360575025",
            "name": "Jon Snow",
            "contact":"1515555-99"
        },
        {
            "id": 2,
            "document": "07984267010334",
            "name": "STARBUCKS",
            "contact":"starbucks@gmail.com"

        }
    ],
    donations: [
        {
            quantity: 10,
            demand: 1,
            donator:2,
        }
    ],

    createDonation: function(data){
        return new Promise((resolve, reject) => {
            data = {
                id: this.donations[this.donations.length-1].id + 1,
                ...data,
            }
            this.donations.push(data)
            const response = {
                status: 201,
                statusText: "Created",
                data: '',
            };
            resolve(response);
        })
    },
    updateDonation: function(donationId, data){
        const index = this.donations.findIndex(donation => donation.id === donationId);
        const newDonation = {
            id: donatorId,
            document: data.document,
            name: data.name,
        };

        this.donations.splice(index, 1, newDonation);
        const response = {
            status: 200,
            statusText: "Ok",
            data: newDonation
        };
        return new Promise((resolve, reject) => {
            resolve(response);
        });
    },
    indexDonationsByDemand: function() {
        return new Promise((resolve, reject) => {
            const data = {
                "current_page": 1,
                "data": this.donations,
                "first_page_url": "http://localhost:8092/api/demand/1/donations?page=1",
                "from": 1,
                "last_page": 1,
                "last_page_url": "http://localhost:8092/api/demand/1/donations?page=1",
                "next_page_url": null,
                "path": "http://localhost:8092/api/entidemandties/1/donations",
                "per_page": 15,
                "prev_page_url": null,
                "to": 2,
                "total": 2
            };
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },

    createDonator: function(data) {
        return new Promise((resolve, reject) => {                
            data = {
                id: this.donators[this.donators.length-1].id + 1,
                ...data,
            };
            this.donators.push(data);
            const response = {
                status: 201,
                statusText: "Created",
                data: '',
            };
            resolve(response);
        });
    },
    getDonator: function(donatorId) {
        return new Promise((resolve, reject) => {
            const data = this.donators.find(donator => donator.id === donatorId);
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },
    getDonatorByDocument: function(donatorDocument) {
        return new Promise((resolve, reject) => {
            const data = this.donators.find(donator => donator.document === donatorDocument);
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },
    searchDonator: function(query){
        let results = []
        this.donators.forEach(donator => {
            if(donator.name.toLocaleLowerCase().search(query.toLocaleLowerCase()) >= 0)
                results.push(donator)
        })

        return new Promise((resolve, reject) => {
            const data = results
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },
    updateDonator: function(donatorId, data) {
        //const currentDonator = this.donators.find(donator => donator.id === donatorId);
        const index = this.donators.findIndex(donator => donator.id === donatorId);
        const newDonator = {
            id: donatorId,
            document: data.document,
            name: data.name,
        };

        this.donators.splice(index, 1, newDonator);
        const response = {
            status: 200,
            statusText: "Ok",
            data: newDonator
        };
        return new Promise((resolve, reject) => {
            resolve(response);
        });
    },


    indexDemandsByEntity: function() {
        return new Promise((resolve, reject) => {
            const data = {
                "current_page": 1,
                "data": this.demands,
                "first_page_url": "http://localhost:8092/api/entities/1/demands?page=1",
                "from": 1,
                "last_page": 1,
                "last_page_url": "http://localhost:8092/api/entities/1/demands?page=1",
                "next_page_url": null,
                "path": "http://localhost:8092/api/entities/1/demands",
                "per_page": 15,
                "prev_page_url": null,
                "to": 2,
                "total": 2
            };
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },

    createDemand: function(data) {
        return new Promise((resolve, reject) => {
            data = {
                id: this.demands[this.demands.length-1].id + 1,
                ...data,
            };
            this.demands.push(data);
            const response = {
                status: 201,
                statusText: "Created",
                data: '',
            };
            resolve(response);
        });
    },

    getDemand: function(demandId) {
        return new Promise((resolve, reject) => {
            const data = this.demands.find(demand => demand.id === demandId);
            const response = {
                status: 200,
                statusText: "Ok",
                data
            };
            resolve(response);
        });
    },

    updateDemand: function(demandId, data) {
        const currentDemand = this.demands.find(demand => demand.id === demandId);
        const index = this.demands.findIndex(demand => demand.id === demandId);
        const newDemand = {
            id: demandId,
            title: data.title,
            text: currentDemand.text,
            quantity: data.quantity,
        };

        this.demands.splice(index, 1, newDemand);
        const response = {
            status: 200,
            statusText: "Ok",
            data: newDemand
        };
        return new Promise((resolve, reject) => {
            resolve(response);
        });
    },

    deleteDemand: function(demandId) {
        return new Promise((resolve, reject) => {
            const index = this.demands.findIndex(demand => demand.id === demandId);
            this.demands.splice(index, 1);
            const response = {
                status: 204,
                statusText: "No Content",
                data: '',
            };
            resolve(response);
        });
    },
};

export default api;