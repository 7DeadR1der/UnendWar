"use strict";
let idIndex = 1;
const players = new Array();
const gameSettings = {
    turnOwner: 0
};
class Player{
    constructor(name, num){
        this.name = name;
        this.owner = num;
        //this.color
        this.gold = 0;
        this.counts = 0
    }
}

class Unit{
    constructor(arrInf, owner){
        this.type = arrInf[0];
        this.class = arrInf[1];
        this.name = arrInf[2];
        this.description = arrInf[3];
        this.hpMax = arrInf[4];
        this.hp = arrInf[4];
        this.attack = arrInf[5];
        this.movePoint = arrInf[6];
        this.range = arrInf[7];
        //this.ability = arrInf[8];
        this.canMove = true;
        this.canAction = true;
        this.owner = owner;
        this.id = idIndex++;

        
    }
}

class Building{

}

class Warchief{

}

let peasant = ['unit','T1','Peasant','description',1,1,1,1,0];
let scout2 = ['unit','T2','Scout','description',1,1,2,1,1];
//let townhall = [];
let townhall = {
    type: 'building',
    class: 'Townhall',
    name: 'Townhall',
    description: 'building test',
    canMove: undefined,
    canAction: undefined,
    hp: 5,
    attack: 0,
    movePoint: 0,
    owner:1
};

let exampleUnit = {
    type: 'unit/building',
    class: 'T1/T2/T3/Warchief/Townhall/Tower',
    name: 'name)',
    description: 'example unit',
    canMove: true,
    canAction: true,
    hp: 1,
    attack: 1,
    movePoint: 1,
    range: 1,
    owner: 0


};