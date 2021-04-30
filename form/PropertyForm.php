<?php

class PropertyForm extends Form {
    const _entries = [
        [
            "kind" => "hidden",
            "id" => "id",
            "label" => "id",
        ],
        [
            "kind" => "text",
            "id" => "ref",
            "label" => "réference",
        ],
        [
            "kind" => "text",
            "id" => "description",
            "label" => "description",
        ],
        [
            "kind" => "text",
            "id" => "address",
            "label" => "addresse",
        ],
        [
            "kind" => "text",
            "id" => "postalCode",
            "label" => "code postal",
        ],
        [
            "kind" => "number",
            "id" => "livingRoomsNumber",
            "label" => "nombre de salle à vivre",
        ],
        [
            "kind" => "text",
            "id" => "town",
            "label" => "ville",
        ],
        [
            "kind" => "option",
            "id" => "kind",
            "label" => "genre",
            "options" => [
                [
                    "id" => "house",
                    "label" => "Maison",
                ],
                [
                    "id" => "apartment",
                    "label" => "Appartement",
                ],
                [
                    "id" => "land",
                    "label" => "Terrain",
                ],
                [
                    "id" => "carpark",
                    "label" => "Terrain de voiture",
                ],
                [
                    "id" => "other",
                    "label" => "Autre",
                ]
            ],
        ]
    ];
}