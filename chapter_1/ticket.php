<?php

// 초대장
class Invitation
{
    // 초대일자
    /** @var int */
    private $when;
}

// 티켓
class Ticket
{
    /** @var int */
   private $fee;

    public function getFee()
    {
        return $this->fee;
    }
}

// 가
class Bag
{
    /** @var int */
    private $amount;
    /** @var Invitation */
    private $invitation;
    /** @var Ticket */
    private $ticket;

    // 가방의 옵션은 2가지
    // 이벤트 당첨: 초대장, 현금
    // 이벤트 당첨x: 초대장(x), 현금
    public function __construct(Invitation $invitation, $amount){
        $this->invitation = $invitation;
        $this->amount = $amount;
    }

    // 초대장 보유 여부 판단
    public function hasInvitation(){
        return $this->invitation !== null;
    }

    // 티켓 소유 여부 판단
    public function hasTicket(){
        return $this->ticket !== null;
    }

    // 초대장을 티켓으로 변경
    public function setTicket(Ticket $ticket){
        $this->ticket = $ticket;
    }

    // 현금 감소
    public function minusAmount($amount){
        $this->amount -= $amount;
    }

    // 현금 증가
    public function plusAmount($amount){
        $this->amount += $amount;
    }
}

// 관람객: 가방을 가지고 있음.
class Audience
{
    /** @var Bag */
    private $bag;

    public function __construct(Bag $bag) {
        $this->bag = $bag;
    }

    public function getBag()
    {
        return $this->bag;
    }
}

// 매표소
class TicketOffice
{
    // 티켓 판매 금액
    /** @var int */
    private $amount;
    /** @var Ticket */
    private $ticket;

    public function __construct($amount, Ticket $ticket)
    {
        $this->amount = $amount;
        $this->ticket = $ticket;
    }

    public function getTicket(){
        return $this->ticket;
    }

    public function minusAmount($amount)
    {
        $this->amount -= $amount;
    }

    public function plusAmount($amount)
    {
        $this->amount += $amount;
    }
}

// 판매원: 매표소에서 초대장을 티켓으로 교환 or 티켓 판매
class TicketSeller
{
    /** @var TicketOffice */
    private $ticketOffice;

    public function __construct(TicketOffice $ticketOffice)
    {
        $this->ticketOffice = $ticketOffice;
    }

    /**
     * @return TicketOffice
     */
    public function getTicketOffice()
    {
        return $this->ticketOffice;
    }

}

// 소극장
class Theater
{
    /** @var TicketSeller */
    private $ticketSeller;

    public function __construct(TicketSeller $ticketSeller)
    {
        $this->ticketSeller = $ticketSeller;
    }

    public function enter(Audience $audience){
        if ($audience->getBag()->hasInvitation()) {
            $ticket = $this->ticketSeller->getTicketOffice()->getTicket();
            $audience->getBag()->setTicket($ticket);
        } else {
            $ticket = $this->ticketSeller->getTicketOffice()->getTicket();
            $audience->getBag()->minusAmount($ticket->getFee());
            $this->ticketSeller->getTicketOffice()->plusAmount($ticket->getFee());
            $audience->getBag()->setTicket($ticket);
        }

    }
}