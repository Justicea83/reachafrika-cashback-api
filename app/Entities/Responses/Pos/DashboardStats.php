<?php

namespace App\Entities\Responses\Pos;

class DashboardStats
{
    public string $merchantName;
    public string $myName;
    public string $posCode;
    public string $salesThisWeek;
    public string $salesThisMonth;
    public string $salesToday;
    public int $notificationCount;

    public static function instance(): DashboardStats
    {
        return new DashboardStats();
    }

    /**
     * @param string $merchantName
     * @return DashboardStats
     */
    public function setMerchantName(string $merchantName): DashboardStats
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * @param string $myName
     * @return DashboardStats
     */
    public function setMyName(string $myName): DashboardStats
    {
        $this->myName = $myName;
        return $this;
    }

    /**
     * @param string $posCode
     * @return DashboardStats
     */
    public function setPosCode(string $posCode): DashboardStats
    {
        $this->posCode = $posCode;
        return $this;
    }



    /**
     * @param int $notificationCount
     * @return DashboardStats
     */
    public function setNotificationCount(int $notificationCount): DashboardStats
    {
        $this->notificationCount = $notificationCount;
        return $this;
    }

    /**
     * @param string $salesThisWeek
     * @return DashboardStats
     */
    public function setSalesThisWeek(string $salesThisWeek): DashboardStats
    {
        $this->salesThisWeek = $salesThisWeek;
        return $this;
    }

    /**
     * @param string $salesThisMonth
     * @return DashboardStats
     */
    public function setSalesThisMonth(string $salesThisMonth): DashboardStats
    {
        $this->salesThisMonth = $salesThisMonth;
        return $this;
    }

    /**
     * @param string $salesToday
     * @return DashboardStats
     */
    public function setSalesToday(string $salesToday): DashboardStats
    {
        $this->salesToday = $salesToday;
        return $this;
    }
}
