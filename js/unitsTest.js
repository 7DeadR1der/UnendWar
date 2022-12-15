"use strict";

let peasant = {
    type: 'unit',
    class: 'T1',
    name: 'Peasant',
    description: 'T1 unit test',
    posMove: true,
    posAttack: true,
    hp: 1,
    attack: 1,
    movePoint: 1,
    owner:1
};
let townhall = {
    type: 'building',
    class: 'Townhall',
    name: 'Townhall',
    description: 'building test',
    posMove: undefined,
    posAttack: undefined,
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
    posMove: true,
    posAttack: true,
    hp: 1,
    attack: 1,
    movePoint: 1,
    range: 1,
    owner: 0


};