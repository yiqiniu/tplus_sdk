<?php
/**
 * Created by PhpStorm.
 * User: gjianbo
 * Date: 2018/8/7
 * Time: 17:26
 */

namespace yqn\chanjet;


/**
 * 合作伙伴
 * Class Partner
 * @package yqn\chanjet
 */
class IPartner  extends IBaseSdk
{

    // 用于查询的字段
    public $queryField=[
        "Code"=>"",
        "Name"=> "",
        "PartnerAbbName"=> "",
        "PartnerType"=>["Code"=> ""],
        "PartnerClass"=>["Code"=> ""],
        "SettlementPartner" => ["Code" => ""],
        "SaleDepartment" => ["Code" => ""],
        "SaleMan" => ["Code" => ""],
        "Disabled" => "True",
        "PartnerAddress" => [
            "AddressJc" => "",
            "ShipmentAddress" => "",
            "Contact" => "",
            "MobilePhone" => "",
            "TelephoneNo" => "",
            "Fax" => "",
            "EmailAddr" => "",
            "QqNo" => "",
            "MsnAddress" => "",
            "UuNo" => ""
        ],
        "SelectFields" => "ID,Code,Name,Shorthand,PartnerAbbName, PartnerType.Code,PartnerType.Name, 
            PartnerClass.Code,PartnerClass.Name, SettlementPartner.Code,
            SettlementPartner.Name,  SaleDepartment.Code, SaleDepartment.Name,  SaleMan.Code,SaleMan.Name, 
            District.Code,District.Name, PriceGrade.Code,PriceGrade.Name, Disabled,CreditBalance,ARBalance,
            AdvRBalance,APBalance,AdvPBalance,Ts,PartnerAddresDTOs.Code,PartnerAddresDTOs.Name,
            PartnerAddresDTOs.Shorthand"
        ];


}