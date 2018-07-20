<?php
/*
  PubNub 整个项目的初始化类
  
  
  属性：
  
  const SDK_VERSION
  const SDK_NAME
  public static $MAX_SEQUENCE
  protected $configuration
  protected $subscriptionManager
  protected $logger
  protected $nextSequence
  
  实现功能：
  __construct
  addListener
  removeListener
  publish
  subscribe
  history
  hereNow
  whereNow
  grant
  audit
  revoke
  addChannelToChannelGroup
  removeChannelFromChannelGroup
  removeChannelGroup
  listChannelsInChannelGroup
  time
  addChannelToPush
  removeChannelsFromPush
  removeAllPushChannelsForDevice
  listPushProvisions
  timestamp
  getSdkVersion
  getSdkName
  getSdkFullName
  getConfiguration
  getBasePath
  getLogger
  setLogger
  getState -> new GetState($this)
  setState -> new SetState($this)
  getSequenceId
*/

// 定义命名空间
namespace PubNub;

// use Monolog\Logger 库
use Monolog\Logger;
use PubNub\Builders\SubscribeBuilder;
use PubNub\Callbacks\SubscribeCallback;
use PubNub\Endpoints\Access\Audit;
use PubNub\Endpoints\Access\Grant;
use PubNub\Endpoints\Access\Revoke;
use PubNub\Endpoints\ChannelGroups\AddChannelToChannelGroup;
use PubNub\Endpoints\ChannelGroups\ListChannelsInChannelGroup;
use PubNub\Endpoints\ChannelGroups\RemoveChannelFromChannelGroup;
use PubNub\Endpoints\ChannelGroups\RemoveChannelGroup;
use PubNub\Endpoints\History;
use PubNub\Endpoints\Presence\GetState;
use PubNub\Endpoints\Presence\HereNow;
use PubNub\Endpoints\Presence\SetState;
use PubNub\Endpoints\Presence\WhereNow;
use PubNub\Endpoints\PubSub\Publish;
use PubNub\Endpoints\Push\AddChannelsToPush;
use PubNub\Endpoints\Push\ListPushProvisions;
use PubNub\Endpoints\Push\RemoveChannelsFromPush;
use PubNub\Endpoints\Push\RemoveDeviceFromPush;
use PubNub\Endpoints\Time;
use PubNub\Managers\BasePathManager;
use PubNub\Managers\SubscriptionManager;


class PubNub
{
    // const 定义两个常量类属性 SDK_VERSION, SDK_NAME 
    const SDK_VERSION = "4.0.0";
    const SDK_NAME = "PubNub-PHP";

    // 公共的静态属性 最大的序列-MAX_SEQUENCE
    public static $MAX_SEQUENCE = 65535;
    
    // 受保护的属性 $configuration
    /** @var PNConfiguration */
    protected $configuration;

    // 受保护的属性 $basePathManager
    /** @var  BasePathManager */
    protected $basePathManager;
    
    // subscriptionManager
    /** @var  SubscriptionManager */
    protected $subscriptionManager;

    // loggrt
    /** @var  Logger */
    protected $logger;

    // $nextSequence
    /** @var  int $nextSequence */
    protected $nextSequence = 0;

    /**
     * PNConfiguration constructor.
     *
     * @param $initialConfig PNConfiguration
     */
    public function __construct($initialConfig)
    {
        // 初始化配置信息
        $this->configuration = $initialConfig;
        // 实例化 BasePathManager
        $this->basePathManager = new BasePathManager($initialConfig);
        // 实例化 SubscriptionManager
        $this->subscriptionManager = new SubscriptionManager($this);
        // 实例化 Logger
        $this->logger = new Logger('PubNub');
    }

    /**
     * Pre-configured PubNub client with demo-keys
     * @return static
     */
    public static function demo()
    {
        return new static(PNConfiguration::demoKeys());
    }

    /**
     * @param SubscribeCallback $listener
     */
    public function addListener($listener)
    {
        // 添加监听器 addListener
        $this->subscriptionManager->addListener($listener);
    }

    /**
     * @param SubscribeCallback $listener
     */
    public function removeListener($listener)
    {
        // removeListener
        $this->subscriptionManager->removeListener($listener);
    }

    /**
     * @return Publish
     */
    public function publish()
    {
        // 实例化 Publish
        return new Publish($this);
    }

    /**
     * @return SubscribeBuilder
     */
    public function subscribe()
    {
        // 实例化 subscribeBuilder，并传入 subscriptionManager
        return new SubscribeBuilder($this->subscriptionManager);
    }

    /**
     * @return History
     */
    public function history()
    {
        // 实例化 History
        return new History($this);
    }

    /**
     * @return HereNow
     */
    public function hereNow()
    {
        // 实例化 HereNow
        return new HereNow($this);
    }

    /**
     * @return WhereNow
     */
    public function whereNow()
    {
        // 实例化 WeherNow
        return new WhereNow($this);
    }

    /**
     * @return Grant
     */
    public function grant()
    {
        // 实例化 grant
        return new Grant($this);
    }

    /**
     * @return Audit
     */
    public function audit()
    {
        // 实例化 Audit
        return new Audit($this);
    }

    /**
     * @return Revoke
     */
    public function revoke()
    {
        return new Revoke($this);
    }

    /**
     * @return AddChannelToChannelGroup
     */
    public function addChannelToChannelGroup()
    {
        // 实例化 AddChannelToChannelGroup
        return new AddChannelToChannelGroup($this);
    }

    /**
     * @return RemoveChannelFromChannelGroup
     */
    public function removeChannelFromChannelGroup()
    {
        // Initialize RemoveChannelFromChannelGroup
        return new RemoveChannelFromChannelGroup($this);
    }

    /**
     * @return RemoveChannelGroup
     */
    public function removeChannelGroup()
    {
        // Initialize RemoveChannelGroup
        return new RemoveChannelGroup($this);
    }

    /**
     * @return ListChannelsInChannelGroup
     */
    public function listChannelsInChannelGroup()
    {
        // Initialize Class ListChannelsInChannelGroup
        // list all the channels in channel group
        return new ListChannelsInChannelGroup($this);
    }

    /**
     * @return Time
     */
    public function time()
    {
        // initialize the inner-class Time
        return new Time($this);
    }

    /**
     * @return AddChannelsToPush
     */
    public function addChannelsToPush()
    {
        // initialize the class AddChannelsToPush
        return new AddChannelsToPush($this);
    }

    /**
     * @return RemoveChannelsFromPush
     */
    public function removeChannelsFromPush()
    {
        // initialize the clas RemoveChannelsFromPush
        return new RemoveChannelsFromPush($this);
    }

    /**
     * @return RemoveDeviceFromPush
     */
    public function removeAllPushChannelsForDevice()
    {
        return new RemoveDeviceFromPush($this);
    }

    /**
     * @return ListPushProvisions
     */
    public function listPushProvisions()
    {
        return new ListPushProvisions($this);
    }

    /**
     * @return int
     */
    public function timestamp()
    {
        return time();
    }

    /**
     * @return string
     */
    static public function getSdkVersion()
    {
        // return the const SDK_VERSION by static::SDK_VERSION
        return static::SDK_VERSION;
    }

    /**
     * @return string
     */
    static public function getSdkName()
    {
        // return the const SDK_NAME by static::SDK_NAME
        return static::SDK_NAME;
    }

    /**
     * @return string
     */
    static public function getSdkFullName()
    {
        $fullName = static::SDK_NAME . "/" . static::SDK_VERSION;

        return $fullName;
    }

    /**
     * Get PubNub configuration object
     *
     * @return PNConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return string Base path
     */
    public function getBasePath()
    {
        return $this->basePathManager->getBasePath();
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return GetState
     */
    public function getState()
    {
        return new GetState($this);
    }

    /**
     * @return SetState
     */
    public function setState()
    {
        return new SetState($this);
    }

    /**
     * @return int unique sequence identifier
     */
    public function getSequenceId()
    {
        /* 
            获取最大序号 - static::$MAX_SEQUENCE
            
            如果 $this->nextSequence === static::$MAX_SEQUENCE, $this->nextSequence
            设置为 1
            
            不等，则 $this->nextSequence 加 1
        */
        if (static::$MAX_SEQUENCE === $this->nextSequence) {
            $this->nextSequence = 1;
        } else {
            $this->nextSequence += 1;
        }

        // 返回    
        return $this->nextSequence;
    }
}
