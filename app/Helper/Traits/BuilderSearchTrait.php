<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 7/9/2560
 * Time: 10:11 น.
 */

namespace App\Helper\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait BuilderSearchTrait
{

    //拼接builder
    public function search( $builder, array $inputs = [] )
    {
        $per_page = $inputs[ 'per_page' ] ?? 12;//分页参数
        if( $per_page == "" )
            $per_page = 12;

        $table = $builder->getModel()->getTable();//获取表名

        $inputs = $this->param_filter( $inputs, $table );//过滤参数

        $builder = ( method_exists( __CLASS__, $table ) ) ? $this->$table( $builder, $inputs ) : $this->default( $builder, $inputs );//构造查询，找不到时提供默认方法

        $paginate = $builder->paginate( $per_page );   //分页查询

        return $paginate->appends( $inputs )->toArray();//返回
    }

    //过滤：仅允许表内字段
    private function param_filter( $inputs, $table )
    {

        if( $table == NULL )
            return $inputs;

        //合法字段
        $param = Schema::getColumnListing( $table );
        $param = array_flip( $param );

        return array_intersect_key( $inputs, $param );
    }

    public function Size2Human( $bytes, $decimals = 2 )
    {
        $size = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' ];

        $int = (integer) ( ( strlen( (string) $bytes ) - 1 ) / 3 );

        return number_format( $bytes / pow( 1024, $int ), $decimals ) . $size[ $int ];
    }

    //默认方法
    private function default( $builder, array $inputs )
    {
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function roles( $builder, array $inputs )
    {
        $builder->with( 'permissions' );//外键：角色
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function admins( $builder, array $inputs )
    {
        $builder->with( 'role' );//外键：角色
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                case 'name':
                    $builder->where( 'name', 'like', "%$v%" );
                    break;
                case 'email':
                    $builder->where( 'email', 'like', "%$v%" );
                    break;
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function permissions( $builder, array $inputs )
    {
        $builder->with( 'parent' );
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                case 'name':
                    $builder->where( 'name', 'like', "%$v%" );
                    break;
                case 'route':
                    $builder->where( 'route', 'like', "%$v%" );
                    break;
                case 'parent_id':
                    if( empty( $v ) )
                        $builder->where( $k, NULL );//兼容空值参数
                    break;
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function role_permissions( $builder, array $inputs )
    {
        $builder->with( 'role', 'permission' );
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function packages( $builder, array $inputs )
    {
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                case 'title':
                    $builder->where( 'title', 'like', "%$v%" );
                    break;
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function coupons( $builder, array $inputs )
    {
        $builder->with( 'admin', 'package', 'user_used' );//外键
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function orders( $builder, array $inputs )
    {
        $builder->with( 'user', 'package', 'pay_method' );//外键
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function withdraws( $builder, array $inputs )
    {
        $builder->with( 'user' );//外键
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function files( $builder, array $inputs )
    {
        $builder->with( 'user' );//外键
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                case 'title':
                    $builder->where( 'title', 'like', "%$v%" );
                    break;
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

    private function dump_lists( $builder, array $inputs )
    {
        $builder->with( 'dump', 'user' );//外键
        foreach( $inputs as $k => $v )
        {
            switch( $k )
            {
                default:
                    $builder->where( $k, $v );
            }
        }

        return $builder;
    }

}