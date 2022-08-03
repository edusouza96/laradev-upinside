<?php

namespace LaraDev;

use LaraDev\Support\Cropper;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'genre',
        'document',
        'document_secondary',
        'document_secondary_complement',
        'date_of_birth',
        'place_of_birth',
        'civil_status',
        'cover',
        'occupation',
        'income',
        'company_work',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city',
        'telephone',
        'cell',
        'type_of_communion',
        'spouse_name',
        'spouse_genre',
        'spouse_document',
        'spouse_document_secondary',
        'spouse_document_secondary_complement',
        'spouse_date_of_birth',
        'spouse_place_of_birth',
        'spouse_occupation',
        'spouse_income',
        'spouse_company_work',
        'lessor',
        'lessee',
        'admin',
        'client',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'user', 'id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'user', 'id');
    }

    public function setLessorAttribute($value)
    {
        $this->attributes['lessor'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setLesseeAttribute($value)
    {
        $this->attributes['lessee'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setDocumentAttribute($value)
    {
        $this->attributes['document'] = $this->clearField($value);
    }
    public function getDocumentAttribute($value)
    {
        return substr($value, 0, 3).'.'.substr($value, 3, 3).'.'.substr($value, 6, 3).'-'.substr($value, 9, 2);
    }

    public function setDateOfBirthAttribute($value)
    {
        $this->attributes['date_of_birth'] = $this->convertStringToDate($value);
    }
    public function getDateOfBirthAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setIncomeAttribute($value)
    {
        $this->attributes['income'] = floatVal($this->convertStringToDouble($value));
    }
    public function getIncomeAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function setZipcodeAttribute($value)
    {
        $this->attributes['zipcode'] = floatVal($this->clearField($value));
    }

    public function setTelephoneAttribute($value)
    {
        $this->attributes['telephone'] = floatVal($this->clearField($value));
    }

    public function setCellAttribute($value)
    {
        $this->attributes['cell'] = floatVal($this->clearField($value));
    }

    /**
     Ao editar qualquer campo do usuário, a senha também é alterada impossibilitando efetuar um novo login.
    Solução: Se o input for vazio, remove a posição da atualização com o unset.
    */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            unset($this->attributes['password']);
            return;
        }

        $this->attributes['password'] = bcrypt($value);
    }

    public function setSpouseDocumentAttribute($value)
    {
        $this->attributes['spouse_document'] = $this->clearField($value);
    }
    public function getSpouseDocumentAttribute($value)
    {
        return substr($value, 0, 3).'.'.substr($value, 3, 3).'.'.substr($value, 6, 3).'-'.substr($value, 9, 2);
    }

    public function setSpouseDateOfBirthAttribute($value)
    {
        $this->attributes['spouse_date_of_birth'] = $this->convertStringToDate($value);
    }
    public function getSpouseDateOfBirthAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setSpouseIncomeAttribute($value)
    {
        $this->attributes['spouse_income'] = floatVal($this->convertStringToDouble($value));
    }
    public function getSpouseIncomeAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function setAdminAttribute($value)
    {
        $this->attributes['admin'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function setClientAttribute($value)
    {
        $this->attributes['client'] = ($value === true || $value === 'on' ? 1 : 0);
    }

    public function getUrlCoverAttribute()
    {
        if(!empty($this->cover)){
            return Storage::url(Cropper::thumb($this->cover, 500, 500));
        }

        return '';
    }

    public function scopeLessors($query)
    {
        return $query->where('lessor', true);
    }

    public function scopeLessees($query)
    {
        return $query->where('lessee', true);
    }

    private function clearField(?string $param)
    {
        if(empty($param)){
            return '';
        }

        return str_replace(['.', '-', '/', '(', ')', ' '], '', $param);
    }

    private function convertStringToDate(?string $param)
    {
        if(empty($param)){
            return null;
        }

        list($day, $month, $year) = explode('/', $param);

        return (new \DateTime($year.'-'.$month.'-'.$day))->format('Y-m-d');
    }

    private function convertStringToDouble(?string $param)
    {
        if(empty($param)){
            return null;
        }

        return str_replace(',', '.', (str_replace('.', '', $param)));
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
